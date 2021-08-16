<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Traits\ApiResponser;
use http\Env\Response;
use Illuminate\Http\Request;

class AttributesController extends Controller
{
    use ApiResponser;
    public function index(Category $category): \Illuminate\Http\JsonResponse
    {
        if ($category->parent_id ==null)
        {
            return $this->errorResponse('Can\'t get attribute from main category',400);
        }
        return response()->json($category->attributes()->where('parent_id',null)->get()->load(['values'=>function ($query) {
            $query->where('value_id', null);
        }]));
    }

    public function values(Attribute $attribute,Attribute $child,AttributeValue $value): \Illuminate\Http\JsonResponse
    {
        if ($child->parent_id != $attribute->id && $value->attribute_id == $attribute->id){
            return response()->json([
                'error'=>'error',
                'code'=>400
            ],400);
        }
        return response()->json($child->values($value->id)->get());
    }
}
