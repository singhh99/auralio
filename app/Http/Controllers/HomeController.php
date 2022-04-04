<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
         // $this->middleware('permission');
    }

    public function index()
    {
        $admin_user=DB::table('admin_users')->get('user_id');
        $admin_user=sizeof($admin_user);
        $salons=DB::table('saloons')->where('saloon_status',0)->get('saloon_id');
        $salons=sizeof($salons);
        $bookings=DB::table('customer_booking')->wheredate('created_at',date("Y-m-d"))->get('customer_booking_id');
        $bookings=sizeof($bookings);
        $rescheduled=DB::table('customer_booking')->where('update_status',1)->wheredate('created_at',date("Y-m-d"))->get();
        $rescheduled=sizeof($rescheduled);
        $cancelled=DB::table('customer_booking')->where('booking_status_id',2)->wheredate('created_at',date("Y-m-d"))->get();
        $cancelled=sizeof($cancelled);

        $booking_data=DB::table('customer_booking')->where('booking_status_id',10)->wheredate('created_at',date('Y-m-d'))->whereIn('payment_type',['cash','online'])
        ->get();
        $totalrevenue=0;
        foreach($booking_data as $key){
            $totalrevenue=$totalrevenue+$key->total_price;
        }
        
        return view('dashboard.index',compact('admin_user','salons','bookings','rescheduled','cancelled','totalrevenue'));
    }

   
}
