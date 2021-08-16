<?php

namespace App\Http\Controllers\Api\Country;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class GovernoratesController extends Controller
{
    public function index(Country $country): \Illuminate\Http\JsonResponse
    {
        return response()->json($country->governorates);
    }
}
