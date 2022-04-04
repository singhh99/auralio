<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use route;
use Curl;
use  App\Http\Controllers\CommonController;

class SaloonLoginController extends Controller
{
    //login api for saloon
     public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();
    }

    public function saloon_login(Request $request)
    {
         $owner_mobile=$request->owner_mobile;
         $result=DB::table('saloons')->where('owner_mobile',$request->owner_mobile)->get();
         if(count($result)==0)
         {
          return $this->CommonController->errorResponse('Saloon Does not Exist',200);
         }
         else{
        $otp = mt_rand(1000,9999);
        session(['owner_mobile'=> $owner_mobile]);
        session(['otp' => $otp]);
          DB::table('saloons')->where('owner_mobile',$owner_mobile)->update(['otp'=>$otp]);
        $msg = "Dear Saloon vendor,$otp is your OTP , please enter the otp to proceed.Thank You. ";
        Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'.$owner_mobile.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2')->get();
         return $this->CommonController->successResponse($otp,'OTP send succesfuly',200);
    }

  }

  public function verify_otp(Request $request)
  {
          $otp=$request->otp;
          $owner_mobile=$request->owner_mobile;
         $result=DB::table('saloons')->where('owner_mobile',$request->owner_mobile)->get();
         if(count($result)>0)
         {
            if($result[0]->otp==$otp && $result[0]->owner_mobile==$owner_mobile )
            {
               $salon_details=DB::table('saloons')->where('owner_mobile',$owner_mobile)->get();
               $saloon_id=$salon_details[0]->saloon_id;  
                 if(((count($salon_details) > 0) && $salon_details[0]->saloon_status==0) && $salon_details[0]->admin_approval==1)
                    {  
                        $saloon_details=DB::table('saloons')->where('owner_mobile',$owner_mobile)->get();
                        $saloon_details[0]->Services = DB::table('saloons_services')->where('saloon_id', $saloon_id)->get();
                        $saloon_details[0]->Images = DB::table('saloons_images')->where('saloon_id', $saloon_id)->get();
                        $saloon_details[0]->Slots = DB::table('saloon_slots')->where('saloon_id', $saloon_id)->get(); 
                       return $this->CommonController->successResponse($saloon_details,'Login succesfully with saloon details)',200);
                  }
                    else
                    {
                           $msg="salon not approved";
                           return $this->CommonController->errorResponse($msg,'Login succesfuly but saloon not approved',201);
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
