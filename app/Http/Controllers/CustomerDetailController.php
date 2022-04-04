<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CustomerDetailController extends Controller
{

	public function customer_detail(Request $request,$customer_id)
	{
       $customer_detail=DB::table('customers')->where('customer_id',$customer_id)->get();
       $booking_details=DB::table('customer_booking')->where('customer_id',$customer_id)->get();
       $saloon_id=$booking_details[0]->saloon_id;
        return view('customer_detail.customer_profile',compact('customer_detail','saloon_id'));
	}
    public function customer_latest_order(Request $request,$saloon_id)
    {
    	$customer_detail = DB::table('customer_booking') 
    	                            ->whereRaw('customer_booking.customer_booking_id IN (select MAX(customer_booking.customer_booking_id) FROM customer_booking GROUP BY customer_booking.customer_id)')
	                                ->join('booking_statuses','customer_booking.booking_status_id','=','booking_statuses.booking_status_id')
	                                ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
	                                // ->join('payment_status','customer_booking.payment_status_id','=','payment_status.payment_status_id')
	                                ->join('customers','customer_booking.customer_id','=','customers.customer_id')
	                                ->where('customer_booking.saloon_id', $saloon_id)->get();

	       $data = DB::table('customer_booking')
	                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
	                    ->join('saloons_services','booking_services.service_id','=','saloons_services.saloon_service_id')
	                     ->join('services', 'saloons_services.service_name', '=', 'services.service_id')
	                    ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
	                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id')
	                    ->where('customer_booking.saloon_id', $saloon_id)->get();

	         for($i = 0; $i < count($customer_detail); $i++)
	            {
	                $abc = [];
	                for($j = 0; $j < count($data); $j++)
	                {
	                    if($customer_detail[$i]->customer_booking_id == $data[$j]->customer_booking_id)
	                    {
	                        array_push($abc, $data[$j]);
	                    }
	                }
	                $customer_detail[$i]->services = $abc;
	            }
	                     // dd($customer_detail);
	           return view('customer_detail.index',compact('customer_detail'));                            
    }

    //to get customer order history
     public function customer_odrer_history(Request $request,$customer_id)
     {
       $booking_details=DB::table('customer_booking')->where('customer_id',$customer_id)->get();
       $saloon_id=$booking_details[0]->saloon_id;
      $customer_detail = DB::table('customer_booking')
	                                ->join('booking_statuses','customer_booking.booking_status_id','=','booking_statuses.booking_status_id')
	                                ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
	                                ->join('payment_status','customer_booking.payment_status_id','=','payment_status.payment_status_id')
	                                ->join('customers','customer_booking.customer_id','=','customers.customer_id')
	                                ->where('customer_booking.customer_id', $customer_id)->get();
                     
	    $data = DB::table('customer_booking')
	                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
						->join('saloons_services','saloons_services.saloon_service_id','=','booking_services.service_id')
	                    ->join('services','saloons_services.service_name','=','services.service_id')
	                    ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
	                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id')
	                    ->where('customer_booking.customer_id', $customer_id)->get();
          //dd($data);
	         for($i = 0; $i < count($customer_detail); $i++)
	            {
	                $abc = [];
	                for($j = 0; $j < count($data); $j++)
	                {
	                    if($customer_detail[$i]->customer_booking_id == $data[$j]->customer_booking_id)
	                    {
	                        array_push($abc, $data[$j]);
	                    }
	                }
	                $customer_detail[$i]->services = $abc;
	            }
	          // dd($customer_detail);
	         return view('customer_detail.order_history',compact('customer_detail','saloon_id'));   
     }
}
