<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Route;
use File;
use json;

class CommonController extends Controller
{
    public function upload_image($image, $folder, $folder1=Null)
    {
    $imagename = substr(str_replace(" ", "", microtime()),2).'.'.$image->getClientOriginalExtension();
    $destinationPath = public_path('/images/'.$folder);
    $image->move($destinationPath, $imagename);
    if($folder1)
    {
    $new_path = public_path('/images/'.$folder1.'/');
    copy($destinationPath.'/'.$imagename, $new_path.$imagename);
    }
    return $imagename;
    }

    public function errorResponse($message=Null,$code)
    {
        return response()->json([
            'status'=>'Error',
            'message' => $message,
            'data' => ""
        ], $code);
    }

    public function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'status'=> 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function notification($token,$body,$title)
    {
        $url = "https://fcm.googleapis.com/fcm/send";

        $serverKey = 'AAAAuWzx30A:APA91bFGw3scKULtJMWsBNa4bY5Vu9wKNFCFWWL8j5QHRqow7GxcBztdpm_XWuv52caaS1aXYIN7A3r__Zqi3Zj2liTovr8SUf8mEskuHGjhQMIRILu06VmhqYpqynoB4r9iIkFe01XC';
        // $title = "Vendor Order Details";
        $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        //Send the request
        $result = curl_exec($ch);
        curl_close ($ch);
        return $result;
    }


}
