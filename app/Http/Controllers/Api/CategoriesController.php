<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Category::query()->where('parent_id',null)->get());
    }

    public function subCategory(Category $category): \Illuminate\Http\JsonResponse
    {
        return response()->json($category->children()->get()->load('children','attribute.values'));
    }
}
