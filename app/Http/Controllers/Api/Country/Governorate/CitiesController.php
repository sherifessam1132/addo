<?php

namespace App\Http\Controllers\Api\Country\Governorate;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(Governorate $governorate): \Illuminate\Http\JsonResponse
    {
        return response()->json($governorate->cities);
    }
}
