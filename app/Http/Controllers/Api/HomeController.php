<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Home;
use App\Traits\ApiResponser;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use Helper,ApiResponser;
    public function __construct()
    {
        $this->middleware('auth:api')->except('updateExpired');
    }
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules=[
            'city_id'=>['exists:cities,id'],
        ];
        $request->validate($rules);
        $homes=Home::all()->load(['category.parent','category.children']);
        return response()->json(
            [
                'data'=>$homes->pluck('category')->each(function ($item, $key) use ($request){
                    $children_ids=$item->children->pluck('id')->toArray();
                    $item->advertisements=Advertisement::query()
                        ->whereIn('category_id',array_merge($children_ids, [$item->id]))
                        ->where('status','accepted')
                        ->whereNotNull('expired_at')
                        ->whereDate('expired_at','>',Carbon::now())
                        ->whereIn('city_id',$request->input('city_id') != null ? [$request->input('city_id')] : $this->getCities())
                        ->limit(5)->get();
                }),
                'code'=>200
            ]
        );
        /*return response()->json([
            'data'=>Category::query()->where('parent_id',null)->get()->each(function ($item, $key) use ($request) {
                $sub_category_ids= (array)$item->children()->get()->pluck('id')->toArray();
                $item->advertisements=Advertisement::query()
                    ->whereIn('category_id',$sub_category_ids)
                    ->where('status','accepted')
                    ->whereNotNull('expired_at')
                    ->whereDate('expired_at','>',Carbon::now())
                    ->whereIn('city_id',$request->input('city_id') != null ? [$request->input('city_id')] : $this->getCities())
                    ->limit(5)->get();
            }),
            'code'=>200
        ]);*/
    }
    public function subCategoryAdvertisements(Category $category): \Illuminate\Http\JsonResponse
    {
        $category=$category->load('children');
        $children_ids=$category->children->pluck('id')->toArray();
        return $this->showAll(
            Advertisement::query()
                ->whereIn('category_id',array_merge($children_ids, [$category->id]))
                ->where('status','accepted')
                ->whereNotNull('expired_at')
                ->whereDate('expired_at','>',Carbon::now())
                ->with(['city','client'])
                ->get()
        );
    }
    
    public function updateExpired()
    {
        Advertisement::query()
                ->whereDate('expired_at','<',Carbon::now())
                ->update(['status'=>'expired']);
                
        return $this->successResponse('success',200);
    }
}
