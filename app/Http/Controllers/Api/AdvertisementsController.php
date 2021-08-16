<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\AdvertisementImage;
use App\Models\Category;
use App\Models\Client;
use App\Models\PackageType;
use App\Models\Room;
use App\Traits\ApiResponser;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AdvertisementsController extends Controller
{
    use ApiResponser,Helper;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $GLOBALS['ids']=[];
        if (\request()->input('category_id')){
            array_push($GLOBALS['ids'],(int)\request()->input('category_id'));
            $category=Category::query()->findOrFail(\request()->input('category_id'))->load('children.children');
            $children=$category->children;
            $children->each(function ($item, $key) {
                array_push($GLOBALS['ids'],$item->id);
                if ($item->children){
                    $item->children->each(function ($item, $key){
                        array_push($GLOBALS['ids'],$item->id);
                    });
                }
            });
        }
        $attributes=is_array(request()->input('attributes'))?request()->input('attributes'): [];
        return $this->showAll(
            Advertisement::query()
                ->whereIn('city_id',$this->getCities())
                ->where('status','accepted')
                ->with('city')
                ->when(\request()->input('category_id'),function ($query){
                    return $query->whereIn('category_id',$GLOBALS['ids']);
                })
                ->withCount([
                    'rooms'=> function ($query) {
                        $query->whereHas('messages');
                    },
                    'reactions'=> function ($query) {
                        $query->where('type','2');
                    }
                ])
                ->when($attributes,function ($query,$attributes){
                    if (count($attributes)>0)
                    {
                        $searchAttributes=[];
                        foreach ($attributes as $key=>$value){
                            if ($value != null)
                            {
                                $searchAttributes[$key]=$value;
                            }
                        }
                        if (count($searchAttributes)>0){
                            foreach ($searchAttributes as $key=>$value){
                                $query=$query->whereHas('attributes',function ($query) use ($key,$value){
                                        $query->where('id',$key)->where('value',$value);
                                });
                            }
                        }
                    }
                    return $query;
                })
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules=[
            'name'=>['required'],
            'description'=>['required'],
            'cover'=>['required','image'],
            'video_link'=>['url'],
            'price'=>['required'],
            'category_id'=>['required','exists:categories,id'],
            'city_id'=>['required','exists:cities,id'],
            'attributes'=>['required','array'],
            'images'=>['required','array','min:1','max:30'],
            'images.*'=>['required','image'],
        ];
        $category=Category::query()->find($request->input('category_id'));
        $attributes=$category->attributes()->get()->pluck('id')->toArray();
        foreach ($attributes as $attribute){
            $rules+=[
                'attributes.'.$attribute =>['required']
            ];
        }
        $request->validate($rules);
        $request_data=$request->except(['cover','attributes','images']);
        $request_data['client_id']=auth()->user()->id;
        $request_data['visitors_count']=0;
        $request_data['contacts_count']=0;
        if ($category->parent_id == null)
        {
            return $this->errorResponse('Can\'t Add Main Category to Advertisement',400);
        }
        $cover=$request->file('cover');
        Storage::disk('public')->makeDirectory('uploads/advertisement_covers');
        Storage::disk('public')->makeDirectory('uploads/advertisement_images');
        if ($request->hasFile('cover'))
        {
            $request_data['cover']=time().'_'.$cover->hashname();
            $request->file('cover')->storeAs('public/uploads/advertisement_covers/',$request_data['cover']);
        }
        $advertisement=Advertisement::query()->create($request_data);
        if ($request->has('attributes')&&is_array($request->input('attributes'))) {
            foreach ($request->input('attributes') as $key=>$attribute)
            {
                $attribute_to_save=[$key=>['value'=>$attribute]];
                $advertisement->attributes()->sync($attribute_to_save,false);
            }
        }
        if ($request->has('images')&&is_array($request->images)) {
            foreach ($request->images as $key=>$advertisementImage)
            {
                $image=$request->file('images.'.$key);
                $request_data_images['image']=time().'_'.$image->hashname();
                $request->file('images.'.$key)->storeAs('public/uploads/advertisement_images/',$request_data_images['image']);
                $advertisement->images()->create($request_data_images);
            }
        }
        return  $this->showOne($advertisement,201);
    }

    /**
     * Display the specified resource.
     *
     * @param Advertisement $advertisement
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        $advertisement->update([
            'visitors_count'=>$advertisement->visitors_count + 1
        ]);
        $advertisement=Advertisement::query()
                ->withCount([
                    'rooms'=> function ($query) {
                        $query->whereHas('messages');
                    },
                    'reactions'=> function ($query) {
                        $query->where('type','2');
                    }
                    ])
                ->get()
                ->load(['city','client','category','images','attributesWithValue.attribute','attributesWithValue.attributeValue'])
                ->where('id',$advertisement->id)
                ->first();
        return  $this->showOne($advertisement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Advertisement $advertisement
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->id != $advertisement->client_id)
        {
            return  $this->errorResponse('You can\'t edit this item',400);
        }
        $rules=[
            'name'=>['required'],
            'description'=>['required'],
            'cover'=>['required','image'],
            'video_link'=>['url'],
            'price'=>['required'],
            'category_id'=>['required','exists:categories,id'],
            'city_id'=>['required','exists:cities,id'],
            'attributes'=>['required','array'],
        ];
        $category=Category::query()->find($request->input('category_id'));
        $attributes=$category->attributes()->get()->pluck('id')->toArray();
        foreach ($attributes as $attribute){
            $rules+=[
                'attributes.'.$attribute =>['required']
            ];
        }
        $request->validate($rules);
        $request_data=$request->except(['cover','attributes','images']);
        $request_data['client_id']=auth()->user()->id;
        if ($category->parent_id == null)
        {
            return $this->errorResponse('Can\'t Add Main Category to Advertisement',400);
        }
        $cover=$request->file('cover');
        Storage::disk('public')->makeDirectory('uploads/advertisement_covers');
        Storage::disk('public')->makeDirectory('uploads/advertisement_images');
        if ($request->hasFile('cover'))
        {
            Storage::disk('public')->delete('uploads/advertisement_covers/'.$advertisement->cover);
            $request_data['cover']=time().'_'.$cover->hashname();
            $request->file('cover')->storeAs('public/uploads/advertisement_covers/',$request_data['cover']);
        }
        $advertisement->update($request_data);
        if ($request->has('attributes')&&is_array($request->input('attributes'))) {
            foreach ($request->input('attributes') as $key=>$value)
            {
                $attribute_to_save=[$key=>['value'=>$value]];
                $advertisement->attributes()->sync($attribute_to_save,false);
            }
        }
        return $this->showOne($advertisement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Advertisement $advertisement
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->id != $advertisement->client_id)
        {
            return  $this->errorResponse('You can\'t edit this item',400);
        }
        Storage::disk('public')->delete('uploads/advertisement_covers/'.$advertisement->cover);
        foreach ($advertisement->images as $key=>$advertisementImage)
        {
            Storage::disk('public')->delete('uploads/advertisement_images/'.$advertisementImage->image);
            AdvertisementImage::query()->find($advertisementImage->id)->delete();
        }
        $advertisement->attributes()->detach();
        $advertisement->delete();
        return $this->showOne($advertisement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Advertisement $advertisement
     * @return \Illuminate\Http\JsonResponse
     */
    public function addImageToAdvertisement(Request $request,Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->id != $advertisement->client_id)
        {
            return  $this->errorResponse('You can\'t edit this item',400);
        }
        $rules=['image'=>['required','image'],];
        $request->validate($rules);
        $image=$request->file('image');
        $request_data['image']=time().'_'.$image->hashname();
        $request->file('image')->storeAs('public/uploads/advertisement_images/',$request_data['image']);
        $advertisement->images()->create($request_data);
        return  $this->showOne($advertisement->load('images'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Advertisement $advertisement
     * @param AdvertisementImage $advertisementImage
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteImageFromAdvertisement(Advertisement $advertisement,AdvertisementImage $advertisementImage): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->id != $advertisement->client_id)
        {
            return  $this->errorResponse('You can\'t edit this item',400);
        }
        Storage::disk('public')->delete('uploads/advertisement_images/'.$advertisementImage->image);
        $advertisementImage->delete();
        return $this->successResponse('the image Deleted Successfully',200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Advertisement $advertisement
     * @return \Illuminate\Http\JsonResponse
     */
    public function sponsored(Request $request,Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->id != $advertisement->client_id)
        {
            return  $this->errorResponse('You can\'t sponsored to this item',400);
        }
        $rules=[
            'package_type_id'=>['required','exists:package_types,id'],
            'bill_number'=>['required'],
        ];
        $request->validate($rules);
        $advertisement->update([
            'expired_at'=>Carbon::now()->addDays(30),
            'status'=>'accepted'
        ]);
        $packageType=PackageType::query()->findOrFail($request->input('package_type_id'));
        $advertisementPackageType=$advertisement->packageTypes()->wherePivot('expired_at','>',Carbon::now())->first();
        if ($advertisementPackageType)
        {
            return $this->errorResponse('You Can\'t Add sponsored to this item',400);
        }
        $advertisement->packageTypes()->attach($packageType->id,
            [
                'expired_at'=> Carbon::now()->addDays($packageType->days),
                'bill_number'=>$request->input('bill_number'),
                'visible_current_toClient_perDay_times'=>0,
            ]
        );
        return $this->showOne($advertisement);
    }

    public function room(Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        $room=$advertisement->rooms()->where('buyer',auth()->user()->id)->first();
        if ($room)
        {
            return $this->showOne($room);
        }
        $room=Room::create([
            'name'=>$advertisement->name,
            'advertisement_id'=>$advertisement->id,
            'buyer'=>auth()->user()->id,
            'seller'=>$advertisement->client_id,
        ]);
        return $this->showOne($room);
    }

    public function getSponsored(): \Illuminate\Http\JsonResponse
    {
        return $this->showAll(
            Advertisement::query()->
            where('status','accepted')
                ->whereNotNull('expired_at')
                ->whereDate('expired_at','>',Carbon::now())
                ->whereIn('city_id',$this->getCities())
                ->whereHas('packageTypes')
                ->with(['city','packageTypes','client'])
                ->get()
        );
    }
    public function getUnSponsored(): \Illuminate\Http\JsonResponse
    {
        return $this->showAll(
            Advertisement::query()->
            where('status','accepted')
                ->whereNotNull('expired_at')
                ->whereDate('expired_at','>',Carbon::now())
                ->whereIn('city_id',$this->getCities())
                ->whereDoesntHave('packageTypes')
                ->with(['client','city'])
                ->get()
        );
    }
    public function latest(): \Illuminate\Http\JsonResponse
    {
        return $this->showAll(
            Advertisement::query()->
            where('status','accepted')
                ->whereNotNull('expired_at')
                ->whereDate('expired_at','>',Carbon::now())
                ->whereIn('city_id',$this->getCities())
                ->with(['client','city'])
                ->get()
        );
    }
    public function popular(): \Illuminate\Http\JsonResponse
    {
        $clients=Client::query()->with(['advertisements.client','advertisements.city'])->withCount('advertisements')->get();
        $advertisements=$clients->pluck('advertisements','id')->collapse()->slice(0,10);
        return response()->json($advertisements);
    }

    public function report(Request $request, Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        $rules = [];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties) {
            $rules += [$locale . '.name' => ['required']];
        }
        $request->validate($rules);
        $advertisement->reports()->create($request->all());
        return response()->json([
            'message'=>'report send successfully',
            'code'=> 200
        ]);
    }
}
