<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Client;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use phpDocumentor\Reflection\Type;

class ClientController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profile(): \Illuminate\Http\JsonResponse
    {
        $client=Client::query()->find(auth()->user()->id);
        return $this->showOne($client);
    }

    public function update_profile(Request $request): \Illuminate\Http\JsonResponse
    {
        $client=Client::query()->findOrFail(auth()->user()->id);

        $rules=[];
        if($request->input('password'))
        {
            $rules+=
                [
                    'password'=>
                        [
                            'required',
                            'min:6',
//                            'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                        ]
                ];
        }
        $rules+=[
            'name' => [ 'string', 'max:255'],
            'email' => ['string', 'max:255', Rule::unique('clients','email')->ignore($client->id,'id')],
            'image'=> ['image'],
            'phone' => ['string','max:255', Rule::unique('clients','phone')->ignore($client->id,'id')],
        ];
        $request->validate($rules);
        $request_data=$request->except(['image','password']);
        Storage::disk('public')->makeDirectory('uploads/client_images');
        if($request->hasFile('image'))
        {
            if($client->image != 'default.png'){
                Storage::disk('public')->delete('uploads/client_images/'.$client->image);
            }
            $image=$request->file('image');
            $request_data['image']=time().'_'.$image->hashname();
            \Intervention\Image\Facades\Image::make($request->file('image'))
                ->resize(800, 800, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                })
                ->save(storage_path('app/public/uploads/client_images/'). $request_data['image']);
        }
        if ($request->input('password')) {

            $request_data['password']=Hash::make($request->input('password'));
        }
        $client->update($request_data);
        return $this->showOne(Client::query()->find(auth()->user()->id));
    }

    public function advertisements(): \Illuminate\Http\JsonResponse
    {
        $client=Client::query()->findOrFail(auth()->user()->id);
        return $this->showAll($client->advertisements()->get()->load('city'));
    }

    public function reaction(Request $request,Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        $rules=[
            'type'=>['required',Rule::in([0,1,2])]
        ];
        $request->validate($rules);
        $data=[ auth()->user()->id => ['type' => $request->input('type')]];

        $advertisement->reactions()->sync($data,false);

        return $this->showOne($advertisement->load('reactions'));
    }
    public function disReaction(Request $request,Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        $advertisement->reactions()->detach(auth()->user()->id);
        return $this->showOne($advertisement->load('reactions'));
    }

    public function clientsReaction(Advertisement $advertisement): \Illuminate\Http\JsonResponse
    {
        return $this->showAll($advertisement->reactions()->get(),200);
    }
    public function clientBaseReactionType(Advertisement $advertisement,Request $request): \Illuminate\Http\JsonResponse
    {
        $types=[0,1,2];
        if(in_array((int)$request->input('type'),$types)){
            return $this->showAll($advertisement->reactions()->where('type',(string)$request->input('type'))->get(),200);
        }
        return $this->errorResponse('error in type',400);
    }

    public function reactionAdvertisementClient(Request $request): \Illuminate\Http\JsonResponse
    {
        $client=Client::query()->findOrFail(auth()->user()->id)->load('reactions.city');
        $types=[0,1,2];
        if(in_array((int)$request->input('type'),$types)){
            return $this->showAll($client->reactions,200);
        }
        return $this->errorResponse('error in type',400);

    }
}
