<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }


    public function login (Request $request): \Illuminate\Http\JsonResponse
    {
        $rules=[
            'fb_token'=>['required']
        ];
        if ($request->social_id == null){
            $rules+=[
                'phone' => ['required','string','max:255','exists:clients,phone'],
                'password' => 'required|string|min:6',
            ];
        }
        else{
            $rules+=[
                'social_id' => ['required','string','max:255','exists:clients,social_id'],
            ];
        }
        $request->validate($rules);

        if ($request->social_id == null){
            $client = Client::where('phone', $request->phone)->first();
            if (!$client) {
                return $this->errorResponse2('Client does not exist', 400,'message');
            }
            else{
                if (Hash::check($request->password, $client->password)){
                    $this->removeToken($client);
                    return $this->tokenResponse($client);
                }
                else{
                    return $this->errorResponse2("Password mismatch", 400,'password');
                }
            }
        }else
        {
            $client = Client::where('social_id', $request->social_id)->first();
            if (!$client) {
                return $this->errorResponse2('Client does not exist', 400,'message');
            }
            return $this->tokenResponse($client);
        }
    }

    public function logout()
    {
        $client=$client=Client::findOrFail(auth()->user()->id);
        $this->removeToken($client);
        return response()->json([
            'code'=>200,
            'message'=>'You logout successfully'
        ]);
    }

    protected function tokenResponse(Client $client): \Illuminate\Http\JsonResponse
    {
        $client->fb_token=\request()->input('fb_token');
        $client->save();
        $city=City::query()->findOrFail($client->city_id)->load('governorate.country');
        $country=$city->governorate->country;
        $token = $client->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token,'client'=>$client->load('city'),'country'=>$country];
        return response()->json($response, 200);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules=[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'max:255', Rule::unique('clients','email')],
            'city_id'=>['required','exists:cities,id'],
            'fb_token'=>['required']
            //'image'=> ['required'],
            //'password' => ['required','string', 'min:6', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',],
        ];
        if ($request->social_id == null){
            $rules+=[
                'phone' => ['required','string','max:255', Rule::unique('clients','phone')],
                'password' => ['required','string', 'min:6',],
            ];
        }
        else{
            $rules+=[
                'social_id' => ['required','string','max:255',Rule::unique('clients','social_id')],
            ];
        }
        $request->validate($rules);
        $request_data= $request->except('image','password');
        if ($request->hasFile('image')){
             $request_data['image']=time().'_'.$request->file('image')->hashName();
            $request->file('image')->storeAs('public/uploads/client_images/',$request_data['image']);
        }else{
            if ($request->input('social_id') == null){
                $request_data['image']=$request->input('image')??'default.png';
            }
            else{
                $request_data['image']='default.png';
            }

        }
        if ($request->input('password')){
            $request_data['password']=Hash::make($request->input('password'));
        }
        $client=Client::create($request_data);
        $client=Client::findOrFail($client->id);
        return $this->tokenResponse($client);
    }


    protected function errorResponse2($message, $code,$key): JsonResponse
    {
        $error=(object)[
            'error'=>[
                $key=>(array)[$message]
            ],
            'code'=>$code
        ];
        return response()->json($error,$code);
    }

    protected  function removeToken(Client $client){
        foreach ($client->tokens as $token)
        {
            $token->delete();
        }
    }
}
