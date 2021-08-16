<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\PackageType;
use App\Models\Client;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;


use DateTime;
class WelcomeController extends Controller
{
    public  function index(){
        $page_title = __('site.global.dashboard');
        $page_description = 'Some description for the page';

        $acceptedAdv=Advertisement::where('status','accepted')->get();
        $pendingAdv=Advertisement::where('status','pending')->get();
        $refusedAdv=Advertisement::where('status','refused')->get();

        $now= new DateTime();
        $now->format('Y-m-d H:i:s');
      
        $paidexpiredAdv=DB::table('advertisement_package_type')->where('expired_at','<',$now)->count();
        $expiredAdv=DB::table('advertisements')->where('expired_at','<',$now)->count();
        $expire=$expiredAdv + $paidexpiredAdv;

        $paidAdv=DB::table('advertisement_package_type')->count();

        $x=DB::table('advertisement_package_type')->pluck('advertisement_id')->toArray();
         $nonpaidAdv=Advertisement::whereNotIn('id', $x)->count();
       
        
        

        $packages=PackageType::get();
      
        $advs=DB::table('advertisement_package_type')->leftJoin('package_types', 'advertisement_package_type.package_type_id', '=', 'package_types.id')->get();
       
    
         
        $i=1;
        $clients=Client::orderBy('id','DESC')->take(5)->get();
        
        return view('dashboard.welcome',compact('page_title','page_description','acceptedAdv','pendingAdv','expire','paidAdv','nonpaidAdv','packages','advs','refusedAdv','i','clients'));
    }
}
