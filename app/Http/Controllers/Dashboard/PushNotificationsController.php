<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\AdvertisementNotification;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Client;
use App\Traits\CustomResponser;
use App\Traits\Notify;
use App\Traits\PusherNotification;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use function PHPUnit\Framework\callback;

class PushNotificationsController extends Controller
{
    use CustomResponser,PusherNotification,Notify;
    public function index()
    {
        if (\request()->ajax()){
            $query=request()->has('query')? request()->input('query'):[];
            $search=$query['generalSearch']??null;
            $items=$this->search(Advertisement::query(),Advertisement::SEARCHFIELDS,$search);
            return $this->showAll($items
                ->with(['client','category','packageTypes'
                =>function ($query){
                    $visible_toClient_perDay_times= $query->wherePivot('advertisement_package_type.expired_at','>',Carbon::now())->first()->visible_toClient_perDay_times;
                    $query->wherePivot('advertisement_package_type.expired_at','>',Carbon::now())
                        ->wherePivot('advertisement_package_type.visible_current_toClient_perDay_times','<',$visible_toClient_perDay_times)
                        ;
                }
                ])->whereHas('packageTypes')
                ->get());
        }
        $page_title = __('site.pushNotification.show');
        $page_description = __('site.pushNotification.page_description');
        return view('dashboard.pushNotifications.index',compact('page_title','page_description'));
    }
    public function push(Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        $advertisement=$advertisement->load(['packageTypes'=>function ($query){
            $visible_toClient_perDay_times= $query->wherePivot('expired_at','>',Carbon::now())->first()->visible_toClient_perDay_times;
            $query->wherePivot('expired_at','>',Carbon::now())
//                ->wherePivot('visible_current_toClient_perDay_times','<=',$visible_toClient_perDay_times)
//                ->first()
            ;
        }]);
        if (count($advertisement->packageTypes) > 0){
            if (
                $advertisement->packageTypes[0]->pivot->visible_current_toClient_perDay_times >= $advertisement->packageTypes[0]->visible_toClient_perDay_times
                ||
                $advertisement->packageTypes[0]->pivot->expired_at < Carbon::now()
            ){
                return $this->errorResponse('You Can\'t Push notifications',400);
            }
        }
        else{
            return $this->errorResponse('You Can\'t Push notifications',400);
        }

        $data= [
            'visible_current_toClient_perDay_times'=>$advertisement->packageTypes[0]->pivot->visible_current_toClient_perDay_times+1,
            'bill_number'=>$advertisement->packageTypes[0]->pivot->bill_number,
            'expired_at'=>$advertisement->packageTypes[0]->pivot->visible_current_toClient_perDay_times+1== $advertisement->packageTypes[0]->visible_toClient_perDay_times ? null :$advertisement->packageTypes[0]->pivot->expired_at

        ];
        $advertisement->packageTypes()->wherePivot('expired_at','>',Carbon::now())->detach($advertisement->packageTypes[0]->id);
        $advertisement->packageTypes()->attach($advertisement->packageTypes[0]->id,$data);
        $client_tokens=Client::query()->where('city_id',$advertisement->city_id)->whereNotNull('fb_token')->where('id','<>',$advertisement->client_id)->get()->pluck('fb_token')->toArray();
        $this->topicNotifyByFirebaseTokens($client_tokens,[
            'title'=>$advertisement->name,
            'body'=>$advertisement->description,
            'image'=>$advertisement->cover_path
        ]);
        return  response()->json(['message'=>'the advertisement pushed','code'=>200],200);
    }
}
