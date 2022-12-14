<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth; 
use App\Models\Notification;
use Illuminate\Contracts\Encryption\DecryptException;
use Config,Mail,View,Redirect,Validator,Response; 
use Crypt,okie,Hash,Lang,JWTAuth,Input,Closure,URL; 
use App\Helpers\Helper as Helper;
use Illuminate\Support\Facades\Storage;
use App\Models\Competition;
use App\Models\TeamA;
use App\Models\TeamB;
use App\Models\Toss;
use App\Models\Venue;
use App\Models\Matches;
use App\Models\ReferralCode;
use Session;
use App\User;
use App\Models\WalletTransaction;


class HomeController extends BaseController
{
   
    public function __construct(Request $request) {
        $pages = \DB::table('pages')->get(['title','slug']);
        View::share('static_page',$pages);

        $settings = \DB::table('settings')
                    ->pluck('field_value','field_key')
                    ->toArray();
       
        View::share('settings',(object)$settings);

    }  


    public function myAffiliate(Request $request){

        $referral = $request->user_id??'MAAHI11';
        $user = User::where('user_name',$referral)->first();

        $myAffiliate = User::select('id','name','team_name')
            ->where('reference_code',$user->referal_code)
            ->get();

        $total_user = $myAffiliate->count();

        $myAffiliate->transform(function ($item, $key)   {  
                $wt = WalletTransaction::where('user_id',$item->id)
                    ->where('payment_type',3)
                    ->sum('amount');
                 $item->deposit = round($wt,2);   

                 $winning = WalletTransaction::where('user_id',$item->id)
                    ->where('payment_type',4)
                    ->sum('amount');
                 $item->winning = round($winning,2);



                 return $item;
        });  
        $commission = \DB::table('affiliate_programs')
                                ->where('user_id',$user->id)
                                ->sum('amount');
       

        $total_deposit =  $myAffiliate->sum('deposit');
        $total_winning =  $myAffiliate->sum('winning'); 
            
         return view('myAffiliate',compact('myAffiliate','total_deposit','total_winning','total_user','user','commission'));
    }

    public function page404(Request $request){
         return view('404');
    }
    public function home(Request $request){
      

        $upcomings = Matches::with('teama','teamb')
        ->where('match_abondon',0)
        ->where('status',1)
        ->where('timestamp_start','>=' , time())
        ->limit(6)
        ->orderBy('created_at','ASC')
        ->get();
      
        $completed =  Matches::where('status',2)->latest('date_end')->first();
        
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        //  return view('home',compact('upcomings','completed','remove_header'));
         return view('website.home', compact('upcomings','completed','remove_header'));

    }

    public function liveChat(Request $request){
        return view('liveChat');
    }

    public function FAQS(Request $request)
    {
        $content = \DB::table('pages')
        ->where('slug','faqs')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('faqs',compact('content','remove_header'));
          return view('website.faqs');
   
    }

    public function aboutus(Request $request){

        $content = \DB::table('pages')
        ->where('slug','about-us')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
       // return view('aboutus',compact('content','remove_header'));
        return view('website.about');
        
    }

    public function disclaimer(Request $request){

        $content = \DB::table('pages')
        ->where('slug','disclaimer')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('disclaimer',compact('content','remove_header'));
           return view('website.Discalimer');
        
    }

    public function howTo(Request $request){

        $content = \DB::table('pages')
        ->where('slug','how-to-play')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('howto',compact('content','remove_header'));
        return view('website.play');
        
    }

    public function legality(Request $request){

        $content = \DB::table('pages')
        ->where('slug','legality')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('legality',compact('content','remove_header'));
         return view('website.legality');
        
    }

    public function refund(Request $request){

        $content = \DB::table('pages')
        ->where('slug','refund-policy')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('refund',compact('content','remove_header'));
        return view('website.Refund-Policy');
        
    }
    public function termsAndConditions(Request $request){

        $content = \DB::table('pages')
        ->where('slug','terms-and-conditions')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('terms',compact('content','remove_header'));
         return view('website.t&c');
    }

    public function privacyPolicy(Request $request){
        $content = \DB::table('pages')
        ->where('slug','privacy-policy')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('policy',compact('content','remove_header'));
         return view('website.privacy-policy');
    }

    public function topReferralUser(Request $request){
       // dd($request->all());
        $user = \DB::table('referral_codes')->get()->groupBy('refer_by');
        
        foreach ($user as $key => $value) {
            $refer[$key] = $value->count();
        }
        arsort($refer);
        $html = '<table border="1" cellpadding="5" cellspacing="0" bgcolor="#FCFCFC"><tr><td>Sno.</td><td>Top 50 Referral user</td><td>Total</td></tr>';
        
        try{

            $i=1;
        foreach ($refer as $key => $value) {
            if($i==51){
                break;
            }
            $topRef = \DB::table('users')->where('id',$key)->first();
           if(!empty($topRef)){
                
                $html .= "<tr><td>".$i++."</td><td>".$topRef->name."</td><td>".$value."</td></tr>";
           }
        }
        echo $html.'</table>';
        }catch(\Excecption $e){
            dd($e);
        }
    }
    

    public function contactus(Request $request){

        if($request->method()=="POST"){

        $request->merge(['request_id'=>time()]);
        $request->merge(['title'=> 'web_request']);
        $request->merge(['name' => $request->name]);
        $request->merge(['mobile'=> $request->mobile]);

        $request->merge(['subject'=> 'Enquiry']);
        
        $table_cname = \Schema::getColumnListing('contacts');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {
           
           if(in_array($value, $except )){
                continue;
           } 
            if($request->get($value)){
                $data[$value] = $request->get($value);
           }
        }
        \DB::table('contacts')->insert($data);
        Session::put('status','Your Request successfully submitted!');
        
        }
        $content = \DB::table('pages')
        ->where('slug','contact-us')
        ->first();
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }
        // return view('contactus',compact('content','remove_header'));

        return view('website.help');

    }
    public function getPage(Request $request, $name=null){
        
        $content = \DB::table('pages')
                ->where('slug',$name)
                ->first();
        if( $content==null){
            return view('404',compact('content'));
        }
        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;

        }


        return view('page',compact('content','remove_header'));
    }


    public function howToInstall(Request $request)
    {
         // write here code to capture previous url

    //  $url = URL::previous();
 
    //  $ip = \Request::ip() ;
    //  if (preg_match("(^[0-9]{3}[fstgi]$)i",request()->get('_branch_name')))
    //  $name = request()->get('_branch_name');
    //  else
    //  $name = '010O';
    //  \DB::table('source_urls')->insert([
    //      'source_url' => $url,
    //      'ip' => $ip,
    //      'origin' => $name,
    //  ]);
    //  $remove_header = false;
    //  if($request->get('request')=='mobile'){

    //      $remove_header = true;

    //  }
    //  $is_mobile = false;
    //  if($request->get('utm_orgin')=='mobile'){

    //      $is_mobile = true;

    //  }
    //  return view('install',compact('remove_header','is_mobile'));
     return view('website.install');
     //return response()->download('public/upload/apk/justkhelo.apk');

      
    }
   
    public function liveSchowToInstallore(Request $request)
    {
        
        $match_id = $request->match_id;
        
        if($match_id){
            $url = "https://rest.entitysport.com/v2/matches/".$match_id."/scorecard?token=46b8fd6384083ee959d20e1734119cec";
        
        $mt = \DB::table('live_scores')->where('match_id',$match_id)->first();
        
       /* if($mt){
           // return  json_decode($mt->response,true);    
        }
        $r = json_decode($mt->response);    
        foreach ($r->innings as $key => $value) {
            
           foreach ($value->batsmen as $key => $value) {
              dd($value);
           }
        } */

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $rs = [];
        if ($err) {
            $rs = [];
        } else {
            $data = json_decode($response);
            $rs = $data->response;
             
            \DB::table('live_scores')->updateOrInsert(
                ['match_id'=>$match_id],
                [
                    'match_id' => $match_id,
                    'response' => json_encode($rs)
                ]
            );
            
            //return  (array)$rs; 
        }
        }
        $rs = $rs??[];
        $liveMatch = \DB::table('matches')->where('status',3)->get();
         

        $remove_header = false;
        if($request->get('request')=='mobile'){

            $remove_header = true;
        }
        return view('liveScore',compact('remove_header','rs','liveMatch'));
    }
}
