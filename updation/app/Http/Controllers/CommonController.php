<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Route;
use File;

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

    public function errorResponse($message = null, $code)
    {
        return response()->json([
            'status'=>'Error',
            'message' => $message,
            'data' => null
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

   
}
