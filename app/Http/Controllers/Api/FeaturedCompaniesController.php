<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeaturedCompany;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeaturedCompaniesController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules=[
            'category_id'=>['required','exists:categories,id'],
        ];
        $request->validate($rules);
        $featuredCompany=FeaturedCompany::query()->create([
            'category_id'=>$request->input('category_id'),
            'client_id'=>auth()->user()->getAuthIdentifier(),
            'expired_at'=>Carbon::now()->addDays(30)
        ]);
        return $this->showOne($featuredCompany);
    }
    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->showAll(
            FeaturedCompany::query()
                ->where('expired_at','>',Carbon::now())
                ->get()
                ->load(['client','category'])
        );
    }

}
