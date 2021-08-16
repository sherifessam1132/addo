<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        $this->middleware('auth:api')->only(['current']);
    }
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Country::all(),200);
    }
    public function current(): \Illuminate\Http\JsonResponse
    {
        $client=Client::query()->findOrFail(auth()->user()->id);
        $city=City::query()->findOrFail($client->city_id)->load('governorate.country');
        return $this->showOne($city->governorate->country);
    }
}
