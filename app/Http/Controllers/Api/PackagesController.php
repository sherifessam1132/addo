<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Package::all());
    }

    public function types(Package $package): \Illuminate\Http\JsonResponse
    {
        return response()->json($package->types()->get());
    }
}
