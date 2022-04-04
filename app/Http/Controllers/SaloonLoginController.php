<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Route;
use Curl;
use  App\Http\Controllers\CommonController;
use App\SaloonModel;
class SaloonLoginController extends Controller
{
    //login api for saloon
     public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();
    }

    public function salon_registartion(Request $request)   
    {
       $owner_mobile=$request->owner_mobile;
       $result=DB::table('saloons') ->orderBy('saloon_id','desc')
                                    ->where('owner_mobile',$request->owner_mobile)
                                   ->limit(1)->get();

       if(DB::table('saloons') ->orderBy('saloon_id','desc')
               ->where('saloon_status',0)->where('owner_mobile',$request->owner_mobile)->count() > 0)
       {
         return $this->CommonController->errorResponse('Salon Already Exist',201);
       }
       else{
          $otp = mt_rand(1000,9999);
          $saloon_id=SaloonModel::insertGetId(['owner_mobile'=>$request->owner_mobile]);
           DB::table('saloons')->where('saloon_id',$saloon_id)
                                  ->update(['otp'=>$otp,'saloon_status'=>1]);
           $msg = "Dear Saloon vendor,$otp is your OTP , please enter the otp to proceed.Thank You. ";
           Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'.$owner_mobile.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2')->get();
          return $this->CommonController->successResponse($otp,'OTP send succesfuly',200);
       }

    }

    public function saloon_otp_verification(Request $request)
    {
       if(DB::table('saloons') ->orderBy('saloon_id','desc')
               ->where('saloon_status',0)->where('owner_mobile',$request->owner_mobile)->count() > 0)
       {
         return $this->CommonController->errorResponse('Salon Already Exist',201);
       }
       else{
          
            $saloon_id=SaloonModel::insertGetId(['owner_mobile'=>$request->owner_mobile]);
            DB::table('saloons')->where('saloon_id',$saloon_id)->update(['saloon_status'=>1]);     
           }
     
      $idToken=$request->idToken;
      $owner_mobile=$request->owner_mobile;
      

      $key = 'AIzaSyDYm2hOQM9v0axvz7Rh_aOwJiyO2jQQAvk';
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key='.$key);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"idToken\":\"$idToken\"}");
      
      $headers = array();
      $headers[] = 'Content-Type: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
      $response = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      
      curl_close($ch);
      $response=json_decode($response, true);      
      $diff=$response['users'][0]['providerUserInfo'][0]['rawId']??'';
      $country_code="+91";
      $diff= preg_replace("/^\+?{$country_code}/", '',$diff);
      
        
      $result=DB::table('saloons')->where('owner_mobile',$request->owner_mobile)->where('saloon_status',1)->get();
      
      if(count($result)>0)
     {
       if(($diff==$owner_mobile ) && $result[0]->owner_mobile==$owner_mobile )
        {
         $saloon_details=['saloon_id'=>$result[0]->saloon_id,
                           'owner_mobile'=>$result[0]->owner_mobile];
        DB::table('saloons')->where('owner_mobile',$request->owner_mobile)->update(['saloon_status'=>0 ,'device_id'=>$request->device_id]);
                             
                            
          return $this->CommonController->successResponse($saloon_details,'Login succesfully with saloon details)',200);
        }
         else
         {
            return $this->CommonController->errorResponse('OTP not matched',201);
        }
     }

     else
     {
      return $this->CommonController->errorResponse('Login Failed',201);
     }
  }

  public function verify_otp(Request $request)
  {
          
          $idToken=$request->idToken;
          $owner_mobile=$request->owner_mobile;
          
    
          $key = 'AIzaSyDYm2hOQM9v0axvz7Rh_aOwJiyO2jQQAvk';
          $ch = curl_init();
    
          curl_setopt($ch, CURLOPT_URL, 'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key='.$key);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"idToken\":\"$idToken\"}");
          
          $headers = array();
          $headers[] = 'Content-Type: application/json';
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          
          $response = curl_exec($ch);
          if (curl_errno($ch)) 
          {
            echo 'Error:' . curl_error($ch);
          }
          curl_close($ch);
          $response=json_decode($response, true);
          $diff=$response['users'][0]['providerUserInfo'][0]['rawId']??''; 
          $country_code="+91";
          $diff= preg_replace("/^\+?{$country_code}/", '',$diff);
          
       
         $result=DB::table('saloons')->where('owner_mobile',$request->owner_mobile)
                                      ->where('saloon_status',0)->get();
         if(count($result)>0)
         {
            if(($diff==$owner_mobile) && $result[0]->owner_mobile==$owner_mobile )
            {

               $salon_details=DB::table('saloons')->where('owner_mobile',$owner_mobile)
                                                    ->where('saloon_status',0)->get();
                 DB::table('saloons')->where('owner_mobile',$request->owner_mobile)->update(['device_id'=>$request->device_id]);                                    
               $saloon_id=$salon_details[0]->saloon_id;
                 if(((count($salon_details) > 0) && $salon_details[0]->saloon_status==0) && $salon_details[0]->admin_approval==1)
                    {
                        $day=!empty($salon_details[0]->saloon_working_days) ? json_decode($salon_details[0]->saloon_working_days) : [''];
                        $features=!empty($salon_details[0]->saloon_feature_id)? json_decode($salon_details[0]->saloon_feature_id) : [''];
                        if(empty($day)) {
                            $day = [''];
                        }
                        if(empty($features)) {
                            $features = [''];
                        }
                        $saloon_details=DB::table('saloons')->where('owner_mobile',$owner_mobile)
                                                            ->where('saloon_status',0)->get();
                        $saloon_details[0]->Services = DB::table('saloons_services')->where('saloon_id', $saloon_id)->get();
                        $saloon_details[0]->Images = DB::table('saloons_images')->where('saloon_id', $saloon_id)->get();
                        $saloon_details[0]->Slots = DB::table('saloon_slots')->where('saloon_id', $saloon_id)->get();
                         $saloon_details[0]->days = DB::table('days')->whereIn('day_id',$day)->get();
                          $saloon_details[0]->features = DB::table('features')->whereIn('feature_id',$features)
                          ->select('features.feature_id','features.feature_name')->get();

                       return $this->CommonController->successResponse($saloon_details,'Login succesfully with saloon details)',200);
                  }
                    else
                    {

                           return $this->CommonController->errorResponse('Login succesfuly but saloon not approved',201);
                      }
            }
             else{
              return $this->CommonController->errorResponse('Login Failed',201);
             }
         }
       else
       {
               return $this->CommonController->errorResponse('saloon does not exist',200);
       }

  }

}
