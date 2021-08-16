<?php

use App\Http\Controllers\Dashboard\ProductsController;
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


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ],
    function()
    {
Route::prefix('dashboard')->name('dashboard.')
            ->middleware(['auth'])
            ->group(function (){

                Route::get('/','WelcomeController@index')->name('welcome');
                Route::resource('users','UsersController');
                Route::resource('roles','RolesController');
                Route::resource('categories','CategoriesController');
                Route::resource('attributes','AttributesController');
                Route::get('attributes/{attribute}/child','AttributesController@child')->name('attributes.child_form');
                Route::post('attributes/{attribute}/add_child','AttributesController@addChild')->name('attributes.add_child');
                Route::get('attributes/{attribute}/child/{child}','AttributesController@editChild')->name('attributes.edit_child');
                Route::put('attributes/{attribute}/update_child/{child}','AttributesController@updateChild')->name('attributes.update_child');
                Route::get('attributes/{attribute}/child/{child}/value/{value}','AttributesController@addValues')->name('attributes.addValues');
                Route::post('attributes/{attribute}/child/{child}/value/{value}/store','AttributesController@storeValues')->name('attributes.addValues.store');
                Route::get('attributes/{attribute}/child/{child}/value/{value}/edit','AttributesController@editValues')->name('attributes.editValues');
                Route::put('attributes/{attribute}/child/{child}/value/{value}/update','AttributesController@updateValues')->name('attributes.updateValues');
                Route::resource('countries','CountriesController');
                Route::resource('governorates','GovernoratesController');
                Route::resource('cities','CitiesController');
                Route::resource('clients','ClientsController')->only('index');
                Route::resource('homes','HomesController');
                Route::resource('packages','PackagesController');
                Route::resource('packageTypes','PackageTypesController');
                Route::resource('advertisements','AdvertisementsController')->only(['index','show','update']);
                Route::get('advertisements/{advertisement}/reports','AdvertisementsController@reports')->name('advertisements.reports');
                // delete lecture file
                Route::post('attribute_values/{attributeValue}/delete','AttributesController@delete_attribute_value')->name('attribute.value.delete');
                Route::resource('settings','SettingsController')->only(['index','update']);
                //PushNotificationsController
//                Route::get('PushNotificationsController@index', 'push_notifications')->name('pushNotifications.index');
//                Route::get('push_notifications/{advertisement}','PushNotificationsController@push')->name('pushNotifications.push');

            });

    });
