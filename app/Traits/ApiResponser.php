<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

//use Spatie\Fractal\Fractal;

trait ApiResponser
{
    private function successResponse($data,$code): JsonResponse
    {
        return response()->json($data,$code);
    }
    protected  function errorResponse($message,$code): JsonResponse
    {
        return response()->json(['error'=>$message,'code'=>$code,'data'=>null],$code);
    }
    protected function showAll(Collection $collection,$code=200): JsonResponse
    {
        if ($collection->isEmpty()){
            return $this->successResponse(['data'=>$collection],200);
        }

//        $transformer=$collection->first()->transformer;
        $collection=$this->filterData($collection);
        $collection=$this->sortData($collection);
        $collection=$this->paginate($collection);
//        $collection=$this->transformData($collection,$transformer);
        $collection=$this->cacheResponse($collection);
        return $this->successResponse($collection,200);
    }
    protected function showOne(Model $model,$code=200): JsonResponse
    {
//        $transformer=$model->transformer;
//        $model=$this->transformData($model,$transformer);
        return $this->successResponse($model,200);
    }
    protected function showMessage($message,$code=200): JsonResponse
    {
        return $this->successResponse(['data'=>$message],200);
    }

    protected function filterData(Collection $collection): Collection
    {
        if (request()->input('query') != null || request()->input('query') != '')
        {
            $queries = explode(',', request()->input('query'));
            foreach ($queries as $query){
                $to_search = explode(':', $query);
                //            $attribute=$transformer::originalAttribute($query);
                if (count($to_search)==2)
                {
                    if (isset($to_search[0],$to_search[1])&&($to_search[1]!=null ||$to_search[1]!='')){
                        $collection=$collection->where($to_search[0],$to_search[1]);
                    }
                }else{
                    if (isset($to_search[0],$to_search[1],$to_search[2])&&($to_search[2]!=null ||$to_search[2]!='')){
                        if ($to_search[1] == 'like'){
                            $search=$to_search[2];
                            $collection = collect($collection)->filter(function ($item) use ($search) {
                                return false !== stripos($item, $search);
                            });
                        }
                        else{
                            $collection=$collection->where($to_search[0],$to_search[1],$to_search[2]);
                        }
                    }
                }
            }
        }
        return $collection;
    }

    protected function paginate(Collection $collection): LengthAwarePaginator
    {
        $rules=[
            'per_page'=>'integer|min:2|max:50'
        ];
        Validator::validate(request()->all(),$rules);
        $page=LengthAwarePaginator::resolveCurrentPage();
        $perPage=15;
        if (request()->has('per_page')){
            $perPage=(int) request()->per_page;
        }
        $result=$collection->slice(($page - 1) * $perPage,$perPage)->values();
        $paginated=new LengthAwarePaginator($result,$collection->count(),$perPage,$page,  [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }


    protected function sortData(Collection $collection): Collection
    {
        if (request()->has('sort_by')){
//            $attribute=$transformer::originalAttribute(request()->sort_by);
            if (request()->sort == 'desc'){
                $collection=$collection->sortByDesc(request()->sort_by);
            }else{
                $collection=$collection->sortBy(request()->sort_by);
            }
        }
        return $collection;
    }

//    protected function transformData($data,$transformer): Fractal
//    {
//        return fractal($data,new $transformer);
//    }

    protected function cacheResponse($data)
    {
        $url=request()->url();
        $queryParams=request()->query();

        ksort($queryParams);
        $queryString=http_build_query($queryParams);
        $fullUrl="{$url}?{$queryString}";

        return Cache::remember($fullUrl,30/60, static function () use ($data){
            return $data;
        });
    }
}
