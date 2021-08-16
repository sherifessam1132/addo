<?php
namespace App\Traits;
use App\Models\City;
use App\Models\Governorate;

trait Helper{
    public function getCities(): array
    {
        $city=City::query()->findOrFail(auth()->user()->city_id)->load('governorate.country');
        $country=$city->governorate->country;
        if(request()->input('governorate_id')){
            return City::query()
            ->whereIn(
                'governorate_id',
                Governorate::query()->where('id',request()->input('governorate_id'))->get()->pluck('id')->toArray()
            )->get()->pluck('id')->toArray();
        }
        return City::query()
            ->whereIn(
                'governorate_id',
                Governorate::query()->where('country_id',$country->id)->get()->pluck('id')->toArray()
            )->get()->pluck('id')->toArray();
    }
}
