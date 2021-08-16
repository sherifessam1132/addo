<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

//use Spatie\Fractal\Fractal;

trait CustomResponser
{
    private function successResponse($data,$code): JsonResponse
    {
        $data=(object)$data;
        $sort=request()->has('sort')?request()->input('sort'):['field'=>'id','sort'=>'desc'];
        $pagination=request()->has('pagination')? request()->input('pagination'):[];
        $page=$pagination['page']??LengthAwarePaginator::resolveCurrentPage();
        $perPage=$pagination['perpage']??5;
        $meta=collect(
            (object)['meta' => (object)['page'=>(int)$page,'pages'=>(int)$data->lastPage(),'perpage'=>(int)$perPage,'total'=>(int)$data->total(),'sort'=>$sort['sort'],'field'=>$sort['field']]]
        );
        $data= $meta->merge($data);

        return response()->json($data,$code);
    }
    protected  function errorResponse($message,$code): JsonResponse
    {
        return response()->json(['error'=>$message,'code'=>$code],$code);
    }
    protected function showAll(Collection $collection,$code=200): JsonResponse
    {
//        if ($collection->isEmpty()){
//            return $this->successResponse(['data'=>$collection],200);
//        }
//        $transformer=$collection->first()->transformer;
        $collection=$this->filterData($collection);
//        dd($collection);
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
        if (is_array(request()->input('query')))
        {
            foreach (request()->input('query') as $query => $value){
//            $attribute=$transformer::originalAttribute($query);
                if (isset($query,$value) && $value != '' && $query != 'generalSearch'){
                    $collection=$collection->where($query,$value);
                }
            }
        }
        return $collection;
    }

    protected function paginate(Collection $collection): LengthAwarePaginator
    {

//        $rules=[
//            'per_page'=>'integer|min:2|max:50'
//        ];
        $pagination=request()->has('pagination')? request()->input('pagination'):[];
//        Validator::validate(request()->all(),$rules);
        $page=$pagination['page']??LengthAwarePaginator::resolveCurrentPage();
        $perPage=$pagination['perpage']??5;
        $result=$collection->slice(($page - 1) * $perPage,$perPage)->values();
        $paginated=new LengthAwarePaginator($result,$collection->count(),$perPage,$page,  [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }


    protected function sortData(Collection $collection): Collection
    {
        $sort=request()->has('sort')?request()->input('sort'):['field'=>'id','sort'=>'desc'];
        if (request()->has('sort')){
//            $attribute=$transformer::originalAttribute(request()->sort_by);//,[],$sort['sort'] == 'desc'
            if ($sort['sort'] == 'desc'){
                $collection=$collection->sortByDesc($sort['field']);
            }else{
                $collection=$collection->sortBy($sort['field']);
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


    //Search Before show
    protected function search( Builder $model,$searchFields,$search): Builder //: \Illuminate\Database\Eloquent\Builder
    {
        if ($search != null && $search != '')
        {
            $i=0;
            foreach ($searchFields as $key => $field){
                if (is_numeric($key)){
                    $model =
                        ($i === 0 ) ?
                            $model->where($field,'like','%'.$search.'%')
                            :
                            $model->orWhere($field,'like','%'.$search.'%');
                }
                else{
                    foreach ($field as $item)
                    {
                        $model =
                            ($i === 0 ) ?
                                $model->whereTranslationLike($item,'%'.$search.'%')
                                :
                                $model->orWhereTranslationLike($item,'%'.$search.'%');
                    }
                }

                $i++;
            }
        }
        return $model;
    }
}
