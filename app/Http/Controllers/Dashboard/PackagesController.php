<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Traits\CustomResponser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PackagesController extends Controller
{
    use  CustomResponser;
    public function __construct()
    {
//        $this->middleware('role:admin,package');
//        $this->middleware('permission:view-package,full-permissions')->only('index');
//        $this->middleware('permission:create-package,full-permissions')->only(['create','store']);
//        $this->middleware('permission:update-package,full-permissions')->only(['edit','update']);
//        $this->middleware('permission:delete-package,full-permissions')->only(['delete']);
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
            $items=$this->search(Package::query(),Package::SEARCHFIELDS,$search);
            return $this->showAll($items->get()->makeVisible('action'));
        }
        $page_title = __('site.package.show');
        $page_description = __('site.package.page_description');
        return view('dashboard.packages.index',compact('page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = __('site.package.create');
//        $page_description = __('site.category.description');
        return \view('dashboard.packages.create',compact('page_title'));
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
            $rules += [$locale . '.name' => ['required',Rule::unique('package_translations','name')]];
        }
        $request->validate($rules);
        Package::create($request->all());
        session()->flash('success', __('site.successfully.added'));
        return redirect()->route('dashboard.packages.index');
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
     * @param Package $package
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        $page_title = __('site.package.edit');
//        $page_description = __('site.category.description');
        return \view('dashboard.packages.edit',compact('page_title','package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Package $package): \Illuminate\Http\RedirectResponse
    {
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [
                $locale . '.name' =>
                    [
                        'required',
                        Rule::unique('package_translations','name')->ignore($package->id,'package_id')
                    ]
            ];
        }
        $request->validate($rules);
        $package->update($request->all());
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.packages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Package $package): \Illuminate\Http\RedirectResponse
    {
        if (count($package->types)){
            session()->flash('error', 'The Package Has Types');
            return redirect()->route('dashboard.packages.index');
        }
        $package->delete();
        session()->flash('success', __('site.successfully.deleted'));
        return redirect()->route('dashboard.packages.index');
    }
}
