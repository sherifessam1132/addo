<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//auth Routes
Route::post('login','AuthController@login');
Route::post('register','AuthController@register');
Route::post('logout','AuthController@logout');

//to get cities
Route::get('countries','CountriesController@index');
Route::get('countries/{country}','Country\\GovernoratesController@index');
Route::get('governorates/{governorate}','Country\\Governorate\\CitiesController@index');

//current country
Route::get('country/current','CountriesController@current');

//to get attributes
Route::get('categories','CategoriesController@index');
Route::get('categories/{category}','CategoriesController@subCategory');

Route::get('categories/{category}/attributes','Category\\AttributesController@index');
Route::get('attributes/{attribute}/child/{child}/value/{value}/values','Category\\AttributesController@values');

Route::resource('advertisements','AdvertisementsController')->except(['create','edit']);
//add image to advertisement
Route::post('advertisements/{advertisement}/addImage','AdvertisementsController@addImageToAdvertisement');
//check room
Route::post('advertisements/{advertisement}/room','AdvertisementsController@room');
//delete Image From Advertisement
Route::delete('advertisements/{advertisement}/deleteImage/{advertisementImage}','AdvertisementsController@deleteImageFromAdvertisement');
Route::get('advertisements/{advertisement}/contact',function (\App\Models\Advertisement $advertisement){
    $advertisement->update([
        'contacts_count' => $advertisement->contacts_count + 1
    ]);
    return response()->json($advertisement);
})->middleware('auth:api');
//report
Route::post('advertisements/{advertisement}/report','AdvertisementsController@report');
//sponsored advertisement
Route::post('advertisements/{advertisement}/sponsored','AdvertisementsController@sponsored');
//get sponsored advertisements
Route::get('advertisements/sponsored/all','AdvertisementsController@getSponsored');
//get UnSponsored advertisements
Route::get('advertisements/unSponsored/all','AdvertisementsController@getUnSponsored');
//popular advertisements
Route::get('advertisements/popular/all','AdvertisementsController@popular');

//clientController
Route::get('client/profile','ClientController@profile');
Route::post('client/profile/update','ClientController@update_profile');
Route::get('client/advertisements','ClientController@advertisements');
Route::post('client/reaction/{advertisement}','ClientController@reaction');
Route::delete('client/disReaction/{advertisement}','ClientController@disReaction');
//reactions
Route::get('reactions/{advertisement}','ClientController@clientsReaction');
Route::get('reactions_with_type/{advertisement}','ClientController@clientBaseReactionType');
//reactions advertisement
Route::get('reactions_advertisements','ClientController@reactionAdvertisementClient');
//packages
Route::get('packages','PackagesController@index');
Route::get('packages/{package}','PackagesController@types');

//home
Route::get('home','HomeController@index');
Route::get('home/main_categories/{category}','HomeController@subCategoryAdvertisements');
Route::get('update_expired','HomeController@updateExpired');

//Rooms
Route::get('rooms','RoomsController@index');
Route::get('rooms/{room}','RoomsController@messages');
Route::post('rooms/{room}/message','RoomsController@message');


//featured_companies

Route::get('featured_companies','FeaturedCompaniesController@index');
Route::post('featured_companies','FeaturedCompaniesController@store');
