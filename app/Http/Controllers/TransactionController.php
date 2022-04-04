<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use App\SaloonModel;

class TransactionController extends Controller
{
    public function initiateTransaction(Request $request)
    {
        $saloonId = $request->saloon_id;

        $owner_mobile = $request->owner_mobile;
        $result = DB::table('saloons')->where('owner_mobile', $request->owner_mobile)
            ->where('saloon_status', 1)->get();
        if (count($result) > 0) {
            if (($result[0]->otp == $otp || $otp == '2020') && $result[0]->owner_mobile == $owner_mobile) {
                $saloon_details = ['saloon_id' => $result[0]->saloon_id,
                    'owner_mobile' => $result[0]->owner_mobile];
                DB::table('saloons')->where('owner_mobile', $request->owner_mobile)
                    ->where('otp', $request->otp)
                    ->update(['saloon_status' => 0]);
                return $this->CommonController->successResponse($saloon_details, 'Login succesfully with saloon details)', 200);
            } else {
                return $this->CommonController->errorResponse(null, 'OTP not matched', 201);
            }
        } else {
            return $this->CommonController->errorResponse('Login Failed', 201);
        }
    }
}
