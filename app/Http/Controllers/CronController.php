<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use DateTime;

class CronController extends Controller{

public function update_booking_status(){
           
    $data= DB::table('customer_booking')->where('booking_status_id',3)->get();

    foreach($data as $key){
        $date1=$key->customer_booking_date;
        $convert_date = strtotime($date1);
        $date = Carbon::now();
        $current_date=$date->format('d-m-Y');
        $today_date=strtotime($current_date);

        $timedata=DB::table('saloon_slots')->where('saloon_slot_id',$key->saloon_slot_id)->select('slot_to')->get();
        $timedata=$timedata[0]->slot_to;
        // dd(strtotime($date->format('h:i A'))>strtotime($timedata));
        $date_diff=$today_date-$convert_date;
        
        if($convert_date<$today_date){
            $key->booking_status_id=10;
            } elseif($date_diff==0){
                if(strtotime($date->format('h:i A'))>strtotime($timedata)){
                    $key->booking_status_id=10;

                }
            }    

    }
   foreach($data as $key){
       DB::table('customer_booking')->where('customer_booking_id',$key->customer_booking_id)->update(['booking_status_id'=>$key->booking_status_id]);
    }


   } 

}