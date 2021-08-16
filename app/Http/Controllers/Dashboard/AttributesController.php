<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Traits\CustomResponser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AttributesController extends Controller
{
    use  CustomResponser;
    public function __construct()
    {
//        $this->middleware('role:admin,attribute');
//        $this->middleware('permission:view-attribute,full-permissions')->only('index');
//        $this->middleware('permission:create-attribute,full-permissions')->only(['create','store']);
//        $this->middleware('permission:update-attribute,full-permissions')->only(['edit','update']);
//        $this->middleware('permission:delete-attribute,full-permissions')->only(['delete']);
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
            $items=$this->search(Attribute::query(),Attribute::SEARCHFIELDS,$search);
            return $this->showAll($items->get()->load(['category.parent','child'])->makeVisible('action'));
        }
        $page_title = __('site.attribute.show');
        $page_description = __('site.attribute.page_description');
        return view('dashboard.attributes.index',compact('page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        $main_Categories=Category::query()->where('parent_id',null)->get()->pluck('name','id');
        $page_title = __('site.attribute.create');
//        $page_description = __('site.category.description');
        return \view('dashboard.attributes.create',compact('page_title','main_Categories'));
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
            $rules += [$locale . '.name' => ['required']];
            $rules += ['values.*.'.$locale => ['required']];
        }
        $rules+=[
            'category_id'=>['required','exists:categories,id']
        ];
        $request->validate($rules);
        $request_data=$request->except('values','parent_id');
        $request_data['category_id']=$request->input('sub_category_id') ?? $request_data['category_id'];
        $attribute=Attribute::create($request_data);
        if ($request->has('values')){
            Storage::disk('public')->makeDirectory('uploads/attribute_values_images');
            foreach ($request->input('values') as $key =>$value){
                $request_data_value=[];
                if ($request->hasFile('values.'.$key.'.image'))
                {
                    $request_data_value['image']=time().'_'.$request->file('values.'.$key.'.image')->hashName();
                    $request->file('values.'.$key.'.image')->storeAs('public/uploads/attribute_values_images/',$request_data_value['image']);
                }
                foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties){
                    $request_data_value+=[
                        $locale =>['value'=>$request->input('values.'.$key.'.'.$locale)]
                    ];
                }
                $attribute->values()->create($request_data_value);

            }
        }
        session()->flash('success', __('site.successfully.added'));
        return redirect()->route('dashboard.attributes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Attribute $attribute
     * @return Application|Factory|View
     */
    public function show(Attribute $attribute)
    {
        $attribute=$attribute->load('values');
        $page_title = __('site.attribute.showOne');
//        $page_description = __('site.category.description');
        return \view('dashboard.attributes.show',compact('page_title','attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attribute $attribute
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        $attribute=$attribute->load(['values','category.parent.parent']);
        $main_Categories=Category::query()->where('parent_id',null)
           // ->whereNotIn('parent_id',[$category->id])
            ->get()->pluck('name','id');
        $parent_id=$attribute->category->parent->parent_id == null ? $attribute->category->parent->id:$attribute->category->parent->parent_id ;
        $category_id=$attribute->category->parent->parent_id == null ? $attribute->category->id:$attribute->category->parent->id ;
        $sub_category_id=$attribute->category->parent->parent_id == null ? null:$attribute->category->id;
        $page_title = __('site.attribute.edit');
        return \view('dashboard.attributes.edit',compact('page_title','attribute','parent_id','main_Categories','category_id','sub_category_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Attribute $attribute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Attribute $attribute): \Illuminate\Http\RedirectResponse
    {
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.name' => ['required']];
            $rules += ['values.*.'.$locale => ['required']];
        }
        $rules+=[
            'category_id'=>['required','exists:categories,id']
        ];
        $request->validate($rules);
        $request_data=$request->except('values','parent_id');
        $request_data['category_id']=$request->input('sub_category_id') ?? $request_data['category_id'];
        $attribute->update($request_data);
        if (is_array($request->input('values')))
        {
            foreach ($request->input('values') as $key=> $value)
            {
                if ($request->input('values.'.$key.'.id')!= null){
                    $attributeValue=AttributeValue::find($request->input('values.'.$key.'.id'));
                    if ($attributeValue)
                    {
                        $request_data_value=[];
                        if ($request->hasFile('values.'.$key.'.image'))
                        {
                            if ($attributeValue->image!= 'default.png'){
                                Storage::disk('public')->delete('uploads/attribute_values_images/'.$attributeValue->image);
                            }
                            $request_data_value['image']=time().'_'.$request->file('values.'.$key.'.image')->hashName();
                            $request->file('values.'.$key.'.image')->storeAs('public/uploads/attribute_values_images/',$request_data_value['image']);
                        }
                        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties){
                            $request_data_value+=[
                                $locale =>['value'=>$request->input('values.'.$key.'.'.$locale)]
                            ];
                        }
                        $request_data_value['category_id']= $request_data['category_id'];
                        $attributeValue->update($request_data_value);
                    }
                }else{
                    $request_data_value=[];
                    if ($request->hasFile('values.'.$key.'.image'))
                    {
                        $request_data_value['image']=time().'_'.$request->file('values.'.$key.'.image')->hashName();
                        $request->file('values.'.$key.'.image')->storeAs('public/uploads/attribute_values_images/',$request_data_value['image']);
                    }
                    foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties){
                        $request_data_value+=[
                            $locale =>['value'=>$request->input('values.'.$key.'.'.$locale)]
                        ];
                    }
                    $attribute->values()->create($request_data_value);
                }
            }
        }
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.attributes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attribute $attribute
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Attribute $attribute): \Illuminate\Http\RedirectResponse
    {
        foreach ($attribute->values as $value)
        {
            if ($value->image!= 'default.png'){
                Storage::disk('public')->delete('uploads/attribute_values_images/'.$value->image);
            }
            $value->delete();
        }
        $attribute->delete();
        session()->flash('success', __('site.successfully.deleted'));
        return redirect()->route('dashboard.attributes.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param AttributeValue $attributeValue
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete_attribute_value(AttributeValue $attributeValue): JsonResponse
    {
        if ($attributeValue->image!= 'default.png'){
            Storage::disk('public')->delete('uploads/attribute_values_images/'.$attributeValue->image);
        }
        $attributeValue->delete();
        return \response()->json($attributeValue);
    }

    public function child(Attribute $attribute)
    {
        if ($attribute->child!=null){
            return redirect()->route('dashboard.attributes.edit_child',['attribute'=>$attribute->id,'child'=>$attribute->child->id]);
        }
        $page_title = __('site.attribute.add_child');
        return \view('dashboard.attributes.child.child',compact('page_title','attribute'));
    }
    public function addChild(Request $request,Attribute $attribute)
    {
        if ($attribute->child!=null){
            return redirect()->route('dashboard.attributes.edit_child',['attribute'=>$attribute->id,'child'=>$attribute->child->id]);
        }
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.name' => ['required']];
        }
        $request->validate($rules);
        $request_data=$request->all();
        $request_data['parent_id']=$attribute->id;
        $request_data['category_id']=$attribute->category_id;
        Attribute::create($request_data);
        session()->flash('success', __('site.successfully.create'));
        return redirect()->route('dashboard.attributes.index');
    }
    public function editChild(Attribute $attribute,Attribute $child){
        $page_title = __('site.attribute.edit_child');
        return \view('dashboard.attributes.child.edit_child',compact('page_title','attribute','child'));
    }
    public function updateChild(Request $request,Attribute $attribute,Attribute $child){
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.name' => ['required']];
        }
        $request->validate($rules);
        $request_data=$request->all();
        $request_data['parent_id']=$attribute->id;
        $request_data['category_id']=$attribute->category_id;
        $child->update($request_data);
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.attributes.index');
    }
    public function addValues(Attribute $attribute,Attribute $child,AttributeValue $value){
        if ($child->parent_id != $attribute->id && $value->attribute_id == $attribute->id){
            session()->flash('error', 'error');
            return redirect()->back();
        }
        if ($child->values($value->id)->count() > 0){
            return redirect()->route('dashboard.attributes.editValues',['attribute'=>$attribute->id,'child'=>$attribute->child->id,'value'=>$value->id]);
        }
        $page_title = __('site.attribute.add_values');
        return \view('dashboard.attributes.values.create',compact('page_title','attribute','child','value'));
    }
    public function storeValues(Request $request,Attribute $attribute,Attribute $child,AttributeValue $value){
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += ['values.*.'.$locale => ['required']];
        }
        $request->validate($rules);
        if ($request->has('values')){
            Storage::disk('public')->makeDirectory('uploads/attribute_values_images');
            foreach ($request->input('values') as $key =>$myValue){
                $request_data_value=[];
                $request_data_value['value_id']=$value->id;
                if ($request->hasFile('values.'.$key.'.image'))
                {
                    $request_data_value['image']=time().'_'.$request->file('values.'.$key.'.image')->hashName();
                    $request->file('values.'.$key.'.image')->storeAs('public/uploads/attribute_values_images/',$request_data_value['image']);
                }
                foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties){
                    $request_data_value+=[
                        $locale =>['value'=>$request->input('values.'.$key.'.'.$locale)]
                    ];
                }
                $child->values()->create($request_data_value);

            }
        }
        session()->flash('success', __('site.successfully.added'));
        return redirect()->route('dashboard.attributes.index');
    }
    public function editValues(Attribute $attribute,Attribute $child,AttributeValue $value){
        if ($child->parent_id != $attribute->id && $value->attribute_id == $attribute->id){
            session()->flash('error', 'error');
            return redirect()->back();
        }

        $child=$child->load(['values'=>function ($query) use ($value) {
            $query->where('value_id', $value->id);
        }]);
        $page_title = __('site.attribute.edit_child');
        return \view('dashboard.attributes.values.edit',compact('page_title','attribute','child','value'));
    }
    public function updateValues(Request $request,Attribute $attribute,Attribute $child,AttributeValue $value){
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += ['values.*.'.$locale => ['required']];
        }
        $request->validate($rules);
        if (is_array($request->input('values')))
        {
            foreach ($request->input('values') as $key=> $myValue)
            {
                if ($request->input('values.'.$key.'.id')!= null){
                    $attributeValue=AttributeValue::find($request->input('values.'.$key.'.id'));
                    if ($attributeValue)
                    {
                        $request_data_value=[];

                        if ($request->hasFile('values.'.$key.'.image'))
                        {
                            if ($attributeValue->image!= 'default.png'){
                                Storage::disk('public')->delete('uploads/attribute_values_images/'.$attributeValue->image);
                            }
                            $request_data_value['image']=time().'_'.$request->file('values.'.$key.'.image')->hashName();
                            $request->file('values.'.$key.'.image')->storeAs('public/uploads/attribute_values_images/',$request_data_value['image']);
                        }
                        $request_data_value['value_id']=$value->id;
                        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties){
                            $request_data_value+=[
                                $locale =>['value'=>$request->input('values.'.$key.'.'.$locale)]
                            ];
                        }
                        $attributeValue->update($request_data_value);
                    }
                }else{
                    $request_data_value=[];
                    if ($request->hasFile('values.'.$key.'.image'))
                    {
                        $request_data_value['image']=time().'_'.$request->file('values.'.$key.'.image')->hashName();
                        $request->file('values.'.$key.'.image')->storeAs('public/uploads/attribute_values_images/',$request_data_value['image']);
                    }
                    foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties){
                        $request_data_value+=[
                            $locale =>['value'=>$request->input('values.'.$key.'.'.$locale)]
                        ];
                    }
                    $request_data_value['value_id']=$value->id;
                    $child->values()->create($request_data_value);
                }
            }
        }
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->route('dashboard.attributes.index');
    }
}
