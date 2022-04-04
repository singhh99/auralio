<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaloonLoginController extends Controller
{
    //login api for saloon

    public function saloon_login(Request $request)
    {
        // $owner_mobile=7835833922;
       $owner_mobile=$request->owner_mobile;
          
        $otp = mt_rand(1000,9999);
        session(['owner_mobile'=> $owner_mobile]);
        session(['otp' => $otp]);
        $msg = "Dear Saloon vendor,$otp is your one time password , please enter the otp to proceed.Thank You. ";
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'. $owner_mobile.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
         echo "cURL Error #:" . $err;
        } else {
         echo $response;
         
        }
         return $this->CommonController->successResponse($owner_mobile,' Data Saved succesfuly',200);
    }
}
