<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Traits\CustomResponser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CitiesController extends Controller
{
    use CustomResponser;
    public function __construct()
    {
        $this->middleware('role:admin,city');
        $this->middleware('permission:view-city,full-permissions')->only('index');
        $this->middleware('permission:create-city,full-permissions')->only(['create','store']);
        $this->middleware('permission:update-city,full-permissions')->only(['edit','update']);
        $this->middleware('permission:delete-city,full-permissions')->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|JsonResponse
     */
    public function index()
    {
        if (\request()->ajax()){
            $query=request()->has('query')? request()->input('query'):[];
            $search=$query['generalSearch']??null;
            $items=$this->search(City::query(),City::SEARCHFIELDS,$search);
            return $this->showAll($items->get()->load('governorate')->makeVisible('action'));
        }
        $page_title = __('site.city.show');
        $page_description = __('site.city.page_description');
        return view('dashboard.cities.index',compact('page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        $countries=Country::all()->pluck('name','id');
        $page_title = __('site.city.create');
//        $page_description = __('site.governorate.description');
        return \view('dashboard.cities.create',compact('page_title','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.name' => ['required',Rule::unique('city_translations','name')]];
        }
        $rules+=[
            'governorate_id' => ['required','exists:governorates,id']
        ];
        $request->validate($rules);
        City::create($request->all());
        session()->flash('success', __('site.successfully.added'));
        return redirect()->route('dashboard.cities.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param City $city
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        $countries=Country::all()->pluck('name','id');
        $country=$city->load('governorate.country')->governorate->country;
        $page_title = __('site.country.edit');
//        $page_description = __('site.category.description');
        return \view('dashboard.cities.edit',compact('page_title','city','countries','country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param City $city
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, City $city): \Illuminate\Http\RedirectResponse
    {
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [
                $locale . '.name' => [
                    'required',
                    Rule::unique('city_translations','name')->ignore($city->id,'city_id')
                ]
            ];
        }
        $rules+=[
            'governorate_id' => ['required','exists:governorates,id']
        ];
        $request->validate($rules);
        $city->update($request->all());
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.cities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();
        session()->flash('success', __('site.successfully.deleted'));
        return redirect()->route('dashboard.cities.index');
    }
}
