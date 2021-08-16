<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Home;
use App\Traits\CustomResponser;
use Illuminate\Http\Request;

class HomesController extends Controller
{

    use  CustomResponser;
    public function __construct()
    {
        $this->middleware('role:admin,home');
        $this->middleware('permission:view-home,full-permissions')->only('index');
        $this->middleware('permission:create-home,full-permissions')->only(['create','store']);
        $this->middleware('permission:update-home,full-permissions')->only(['edit','update']);
        $this->middleware('permission:delete-home,full-permissions')->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()//: \Illuminate\Http\JsonResponse
    {
        if (\request()->ajax()){
            return $this->showAll(Home::all()->load('category')->makeVisible('action'));
        }
        $page_title = __('site.home.show');
        $page_description = __('site.home.page_description');
        return view('dashboard.homes.index',compact('page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $main_Categories=Category::query()->where('parent_id',null)->get()->pluck('name','id');
        $page_title = __('site.home.create');
//        $page_description = __('site.category.description');
        return \view('dashboard.homes.create',compact('page_title','main_Categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules=[
            'category_id'=>['required','exists:categories,id']
        ];
        $request->validate($rules);
        Home::query()->create($request->all());
        session()->flash('success', __('site.successfully.added'));
        return redirect()->route('dashboard.homes.index');
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
     * @param Home $home
     * @return \Illuminate\Http\Response
     */
    public function edit(Home $home)
    {
        $main_Categories=Category::query()->where('parent_id',null)
            // ->whereNotIn('parent_id',[$category->id])
            ->get()->pluck('name','id');
        $parent_id=$home->load('category.parent')->category->parent->id;

        $page_title = __('site.home.edit');
        return \view('dashboard.homes.edit',compact('page_title','home','parent_id','main_Categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Home $home
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Home $home)
    {
        $rules=[
            'category_id'=>['required','exists:categories,id']
        ];
        $request->validate($rules);
        $home->update($request->all());
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.homes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Home $home
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Home $home)
    {
        $home->delete();
        session()->flash('success', __('site.successfully.deleted'));
        return redirect()->route('dashboard.homes.index');
    }
}
