<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use DB;
use Session;
use Curl;

class ResetPasswordController extends Controller
{
     
     public function index()
    {
        return view('auth.forgot_password');
    }
    
    public function store(Request $request)
    {
         // $validatedData = $request->validate(['mobile_no' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/' ]]);
        $mobile_no=$request->mobile_no;
         $result=DB::table('users')->where('mobile_no',$request->mobile_no)->get();
         if(count($result)==0)
         {
            Session::flash('message', 'Mobile number does not exist');
            return view('auth.forgot_password');

         }
         else{
            $otp = mt_rand(1000,9999);
            session(['otp' => $otp]);
            session(['mobile_no' => $mobile_no]);
            $msg = "Dear user,$otp is your OTP , please enter the otp to reset your password.Thank You. ";
            Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'.$mobile_no.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2')->get();
            Session::flash('message', 'OTP sent at your registered mobile number');
            return view('auth.forgot_password',compact('otp','mobile_no'));
         }
     }

         public function otp_verify(Request $request)
         {
               $otp=  Session::get('otp');
               $mobile_no=Session::get('mobile_no')
               $user_otp=$request->otp;
              if($otp==$user_otp && $mobile_no==$request->mobile_no )
              {

                return view('auth.update_password');
     
              }
              else
              {
                Session::flash('message', 'OTP does not match');
                return view('auth.forgot_password',compact('otp'));


              }
         }

         public function update_password(Request $request)
         {

            $mobile_no=  Session::get('mobile_no');
            $password=$request->password;
             
             DB::table('users')->where('mobile_no',$mobile_no)->update([
                             'password'=> Hash::make($request->password)]);
             Session::flash('message', 'Password updated sucessfuly');
              return view('auth.login');

         }
       
   


}
