<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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

class GovernoratesController extends Controller
{
    use CustomResponser;
    public function __construct()
    {
        $this->middleware('role:admin,governorate');
        $this->middleware('permission:view-governorate,full-permissions')->only('index');
        $this->middleware('permission:create-governorate,full-permissions')->only(['create','store']);
        $this->middleware('permission:update-governorate,full-permissions')->only(['edit','update']);
        $this->middleware('permission:delete-governorate,full-permissions')->only(['delete']);
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
            $items=$this->search(Governorate::query(),Governorate::SEARCHFIELDS,$search);
            return $this->showAll($items->get()->load('country')->makeVisible('action'));
        }
        $page_title = __('site.governorate.show');
        $page_description = __('site.governorate.page_description');
        return view('dashboard.governorates.index',compact('page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        $countries=Country::all()->pluck('name','id');
        $page_title = __('site.governorate.create');
//        $page_description = __('site.governorate.description');
        return \view('dashboard.governorates.create',compact('page_title','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.name' => ['required',Rule::unique('governorate_translations','name')]];
        }
        $rules+=[
            'country_id' => ['required','exists:countries,id']
        ];
        $request->validate($rules);
        Governorate::create($request->all());
        session()->flash('success', __('site.successfully.added'));
        return redirect()->route('dashboard.governorates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Governorate $governorate
     * @return JsonResponse
     */
    public function show(Governorate $governorate): JsonResponse
    {
        return response()->json($governorate->cities);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Governorate $governorate
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function edit(Governorate $governorate)
    {
        $countries=Country::all()->pluck('name','id');
        $page_title = __('site.governorate.edit');
//        $page_description = __('site.governorate.description');
        return \view('dashboard.governorates.edit',compact('page_title','countries','governorate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Governorate $governorate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Governorate $governorate): \Illuminate\Http\RedirectResponse
    {
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [
                $locale . '.name' => [
                    'required',
                    Rule::unique('governorate_translations','name')->ignore($governorate->id,'governorate_id')
                ]
            ];
        }
        $rules+=[
            'country_id' => ['required','exists:countries,id']
        ];
        $request->validate($rules);
        $governorate->update($request->all());
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.governorates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Governorate $governorate
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Governorate $governorate): \Illuminate\Http\RedirectResponse
    {
        if (count($governorate->cities))
        {
            session()->flash('error', 'The Country Has Cities');
            return redirect()->route('dashboard.governorates.index');
        }
        $governorate->delete();
        session()->flash('success', __('site.successfully.deleted'));
        return redirect()->route('dashboard.governorates.index');
    }
}
