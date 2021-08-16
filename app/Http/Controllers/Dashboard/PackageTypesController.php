<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageType;
use App\Traits\CustomResponser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PackageTypesController extends Controller
{
    use  CustomResponser;
    public function __construct()
    {
//        $this->middleware('role:admin,packageType');
//        $this->middleware('permission:view-packageType,full-permissions')->only('index');
//        $this->middleware('permission:create-packageType,full-permissions')->only(['create','store']);
//        $this->middleware('permission:update-packageType,full-permissions')->only(['edit','update']);
//        $this->middleware('permission:delete-packageType,full-permissions')->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (\request()->ajax()){
            $query=request()->has('query')? request()->input('query'):[];
            $search=$query['generalSearch']??null;
            $items=$this->search(PackageType::query(),PackageType::SEARCHFIELDS,$search);
            return $this->showAll($items->get()->load('package')->makeVisible('action'));
        }
        $page_title = __('site.packageType.show');
        $page_description = __('site.packageType.page_description');
        return view('dashboard.packageTypes.index',compact('page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        $packages=Package::all()->pluck('name','id');
        $page_title = __('site.packageType.create');
//        $page_description = __('site.category.description');
        return \view('dashboard.packageTypes.create',compact('page_title','packages'));
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
        $rules += [
            'price' => ['required','integer'],
            'days' => ['required','integer'],
            'visible_loop_times' => ['required','integer'],
            'visible_toClient_perDay_times' => ['required','integer'],
            'package_id' => ['required','exists:packages,id'],
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.description' => ['required']];
        }
        $request->validate($rules);
        PackageType::create($request->all());
        session()->flash('success', __('site.successfully.added'));
        return redirect()->route('dashboard.packageTypes.index');
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
     * @param PackageType $packageType
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function edit(PackageType $packageType)
    {
        $packages=Package::all()->pluck('name','id');
        $page_title = __('site.packageType.create');
//        $page_description = __('site.category.description');
        return \view('dashboard.packageTypes.edit',compact('page_title','packages','packageType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param PackageType $packageType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, PackageType $packageType)
    {
        $rules = [
            'price' => ['required','integer'],
            'days' => ['required','integer'],
            'visible_loop_times' => ['required','integer'],
            'visible_toClient_perDay_times' => ['required','integer'],
            'package_id' => ['required','exists:packages,id'],
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.description' => ['required']];
        }
        $request->validate($rules);
        $packageType->update($request->all());
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.packageTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PackageType $packageType
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(PackageType $packageType): \Illuminate\Http\RedirectResponse
    {
        $packageType->delete();
        session()->flash('success', __('site.successfully.deleted'));
        return redirect()->route('dashboard.packageTypes.index');
    }
}
