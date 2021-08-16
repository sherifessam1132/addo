<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Client;
use App\Models\AdvertisementReport;
use App\Models\AttributeValue;
use App\Traits\CustomResponser;
use Carbon\Carbon;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AdvertisementsController extends Controller
{
    use CustomResponser,Notify;
    public function __construct()
    {
//        $this->middleware('role:admin,advertisement');
//        $this->middleware('permission:view-advertisement,full-permissions')->only('index');
//        $this->middleware('permission:create-advertisement,full-permissions')->only(['create','store']);
//        $this->middleware('permission:update-advertisement,full-permissions')->only(['edit','update']);
//        $this->middleware('permission:delete-advertisement,full-permissions')->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (request()->ajax()){
            $query=request()->has('query') ? request()->input('query'):[];
            $search=$query['generalSearch']??'';
            $items=$this->search(Advertisement::query(),Advertisement::SEARCHFIELDS,$search);
            return $this->showAll($items->get()->load(['category','client'])->makeVisible('action'));
        }
        $page_title = __('site.advertisement.show');
        $page_description = __('site.advertisement.page_description');
        return view('dashboard.advertisements.index',compact('page_title','page_description'));
        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Advertisement $advertisement
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        $advertisement=$advertisement->load(['category','city','client','images','attributesWithValue.attribute','attributesWithValue.attributeValue','city']);
        $page_title = __('site.advertisement.showOne');
        return view('dashboard.advertisements.show',compact('advertisement','page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Advertisement $advertisement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Advertisement $advertisement): \Illuminate\Http\RedirectResponse
    {
        $rules=[
            'status'=>['required',]
        ];
        $request->validate($rules);
        $request_data=$request->all();
        if ($request_data['status'] == 'accepted')
        {
            $request_data['expired_at']=Carbon::now()->addDays(30);
        }else{
            $request_data['expired_at']=null;
        }
        $advertisement->update($request_data);
        $client_tokens=Client::query()->where('id',$advertisement->client_id)->whereNotNull('fb_token')->get()->pluck('fb_token')->toArray();
        $this->topicNotifyByFirebaseTokens($client_tokens,[
            'title'=>$advertisement->name . 'is ' .$request_data['status'] ,
            'body'=>$advertisement->description,
            'image'=>$advertisement->cover_path
        ]);
        session()->flash('success', __('site.successfully.updated'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reports(Advertisement $advertisement)
    {
        if (request()->ajax()){
            $query=request()->has('query') ? request()->input('query'):[];
            $search=$query['generalSearch']??'';
            $items=$this->search($advertisement->reports()->getQuery(),AdvertisementReport::SEARCHFIELDS,$search);
            return $this->showAll($items->get());
        }
        $page_title = __('site.advertisementReport.show');
        $page_description = __('site.advertisementReport.page_description');
        return view('dashboard.advertisements.reports',compact('page_title','page_description','advertisement'));
    }
}
