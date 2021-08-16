<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check())
    {
        return redirect()->route('dashboard.welcome');
    }
    return redirect()->route('login');
});


\Illuminate\Support\Facades\Auth::routes([
  'register' => false, // Registration Routes...
  'reset' => false, // Password Reset Routes...
  'verify' => false, // Email Verification Routes...
]);

Route::get('/createlink', function(){
    \Artisan::call("storage:link") ;
});

Route::get('time_now',function (){
    return \Carbon\Carbon::now()->toDateTimeString();
});
