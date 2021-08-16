<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Message;
use App\Models\Room;
use App\Traits\ApiResponser;
use App\Traits\Helper;
use App\Traits\Notify;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    use ApiResponser,Helper,Notify;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $client=Client::query()->find(auth()->user()->id);
        return $this->showAll($this->rooms($client)->load('advertisement')->each(function ($item, $key){
            $item->lastMessage= Room::query()->findOrFail($item->id)->messages()->latest()->take(1)->first();
        }));
    }

    public function messages(Room $room): JsonResponse
    {
        return $this->showAll($room->messages()->get()->load('client'));
    }

    public function message(Request $request,Room $room): JsonResponse
    {
        $rules=[
            'message'=>['required']
        ];
        $request->validate($rules);
        $ids=[$room->buyer,$room->seller];
        if(!in_array((int)auth()->user()->id,$ids)){
            return $this->errorResponse2('You can\'t access this Chat',400);
        }
        $message=Message::query()->create([
            'message'=>$request->input('message'),
            'client_id'=> auth()->user()->id,
            'room_id'=>$room->id
        ]);
        $receiver_id=null;
        foreach ($ids as  $id)
        {
            if ($id !=auth()->user()->getAuthIdentifier())
            {
                $receiver_id=$id;
            }
        }
        $receiver_client=Client::query()->findOrFail($receiver_id);
        $this->topicNotifyByFirebaseTokens([$receiver_client->fb_token],[
            'title'=>$receiver_client->name,
            'body'=>$message->message,
        ]);
        return $this->showOne($message->load('client'));
    }


    protected function rooms(Client $client)
    {
        return $client->rooms();
    }

    protected function errorResponse2($message, $code): JsonResponse
    {
        $error=(object)[
            'error'=>[$message],
            'code'=>$code
        ];
        return response()->json($error,$code);
    }
}
