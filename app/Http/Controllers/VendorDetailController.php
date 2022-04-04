<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Http\Component\FCMPush;

class VendorDetailController extends Controller
{
	 public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();

    }
    public function update_order_status(Request $request)
    {

    	$customer_detail = DB::table('customers')->where('customer_id', $request->customer_id)->get();
    	$booking_detail = DB::table('customer_booking')->where('customer_booking_id', $request->customer_booking_id)->get();
    	// dd(count($customer_detail));
        if(count($customer_detail) > 0 && count($booking_detail) >0)
        {
    	  DB::table('customer_booking')->where('customer_booking_id',$request->customer_booking_id)
              ->update(['booking_status_id' =>$request->booking_status_id,
              	        'cancel_by'=>$request->cancel_by,
              	        'cancel_reason'=>$request->cancel_reason]);
						//   dd($booking_detail[0]->saloon_id);
		$saloon_name=DB::table('saloons')->where('saloon_id',$booking_detail[0]->saloon_id)->select('saloon_name')->get();
   	  $saloon_name=$saloon_name[0]->saloon_name;
				
				// if($request->booking_status_id==2){$notify_title= "Your Booking has been Cancelled";}
				// else if($request->booking_status_id==3){$notify_title= "Booking Confirmed";}
			// dd($booking_detail);
				$data1=DB::table('customers')->where('customer_id', $request->customer_id)->get();
				if(!empty($data1[0]->customer_device_id))
				{
					$token=$data1[0]->customer_device_id;
					$payload = [
					'title'=>"",
					'description' =>'',
					'type' => 'payment',
					'customer_booking_id'=>$request->customer_booking_id,
					'user_id' => $request->customer_id,
					'identifier' => 'order-123',
					];
					if($request->booking_status_id==2){$payload['title']= "Booking Cancelled";
					                                    $payload['description']="Dear Customer Your Booking has been cancelled by Salon- '$saloon_name' due to $request->cancel_reason.";}
				else if($request->booking_status_id==3){$payload['title']= "Booking Confirmed";
					$payload['description']="Dear Customer Your Booking for Salon- '$saloon_name' has been confirmed.";}
									
					FCMPush::sendPushToSingleDevice($payload, $token);
		       }

			   $notifdetails=['customer_booking_code'=>$booking_detail[0]->customer_booking_code,'description'=>$payload['description'],'customer_booking_id'=>$booking_detail[0]->customer_booking_id,'customer_id' =>$request->customer_id,'saloon_name' => $saloon_name,'total_price'=>$booking_detail[0]->total_price];
			   DB::table('customer_notifications')->insert($notifdetails);
          return $this->CommonController->successResponse(NULL,'customer status changed Successfully',200);
        }
        else
        {
          return $this->CommonController->errorResponse('customer donot exist',200);
        }
    }
   public function all_order_deatils(Request $request,$saloon_id,$Web=Null)
   {
   	    if($Web)
       {
        	$customer_detail = DB::table('customer_booking')
			// ->whereRaw('customer_booking.customer_booking_id IN (select MAX(customer_booking.customer_booking_id) FROM customer_booking GROUP BY customer_booking.customer_id)')
			->join('booking_statuses','customer_booking.booking_status_id','=','booking_statuses.booking_status_id')
			->join('saloons','saloons.saloon_id','=','customer_booking.saloon_id')
			->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
			// ->join('payment_status','customer_booking.payment_status_id','=','payment_status.payment_status_id')
			->join('customers','customer_booking.customer_id','=','customers.customer_id')
						->where('customer_booking.saloon_id', $saloon_id)->orderBy('customer_booking_id', 'DESC')->get();
// dd($customer_detail);
	         $data = DB::table('customer_booking')
	                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
	                    ->join('services','booking_services.service_id','=','services.service_id')
	                    ->join('saloon_slots','booking_services.saloon_slot_id','=','saloon_slots.saloon_slot_id')
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
	            //   dd($customer_detail);
	              return view('order_detail.index',compact('customer_detail'));
       }
       else
       {
	       	$customer_detail = DB::table('customer_booking')
	                                ->join('booking_statuses','customer_booking.booking_status_id','=','booking_statuses.booking_status_id')
	                                 ->where('saloon_id', $saloon_id)->get();
	         $data = DB::table('customer_booking')
	                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
	                    ->join('services','booking_services.service_id','=','services.service_id')
	                    ->join('saloon_slots','booking_services.saloon_slot_id','=','saloon_slots.saloon_slot_id')
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

	            return $this->CommonController->successResponse($customer_detail,'Booking fetched Successfully',200);
        }
   }

    public function vendor_order(Request $request)
    {
         
         $saloon = DB::table('saloons')->where('saloon_id', $request->saloon_id)->get();
        if(count($saloon)>0)
        {
        	$query1 = DB::table('customer_booking')
		                       ->where('saloon_id', $request->saloon_id);
		                       // ->where('booking_status_id',$request->booking_status_id);
		    $query = DB::table('customer_booking')
		                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
		                    ->join('customers','customers.customer_id','=','customer_booking.customer_id')
		                    ->join('services','booking_services.service_id','=','services.service_id')
		                    ->join('saloon_slots','booking_services.saloon_slot_id','=','saloon_slots.saloon_slot_id')
		                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id', 'customer_booking.customer_booking_date','customers.customer_name')
		                    ->where('customer_booking.saloon_id', $request->saloon_id)->get();


		     //for cancelled booking
		    if($request->booking_status_id==2)
		    {
		         $query1->where('customer_booking.booking_status_id', $request->booking_status_id);

		         $data = $query1->get();

	    	}
	    	//for approved or upcoming order
	    	elseif($request->booking_status_id==3)
	    	{
	    	   $query1->where([
							  [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '>=', date("Y-m-d")]
							])
	    	   		  ->where('booking_status_id',$request->booking_status_id);

               $query->where('customer_booking.booking_status_id', $request->booking_status_id)
               		 ->where([
							  [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '>=', date("Y-m-d")]
							]);
               	$data = $query->get();
               	foreach ($data as $key => $slot)
		    	{
				    if(strtotime($slot->slot_from) < strtotime(date('h:i A')))
				    {
				        unset($data[$key]);
				    }
				}
	    	}
	    	//for  delayed
	    	elseif($request->booking_status_id==10)
	    	{
                $query1->where('booking_status_id',3)
               ->where([
							  [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '<=', date("Y-m-d")]
							]);
               	$query->where('customer_booking.booking_status_id',3)
               	->where([
							  [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '<=', date("Y-m-d")]
							]);

               	$data = $query->get()->toArray();
               	foreach ($data as $key => $slot)
		    	{
				    if(strtotime($slot->slot_to) > strtotime(date('h:i A')) && strtotime($slot->customer_booking_date) >= strtotime(date('q-d-Y')))
				    {
				    	//dd($key);
				        unset($data[$key]);
				    }
				}
	    	}
	    	else
	    	{
	    		return $this->CommonController->errorResponse('no orders',200);
	    	}

	    	$customer_detail = $query1->get();
	    	if(count($customer_detail) > 0 && count($data) > 0)
	    	{
	    		foreach($customer_detail as $key => $dt)
			    {
			        $abc = [];
			        foreach($data as $key1)
			        {
			            if($dt->customer_booking_id == $key1->customer_booking_id)
			            {
			                array_push($abc, $key1);
			            }
			        }
			        if(count($abc) > 0)
			        {
			        	$dt->services = $abc;
			        }
			        else
			        {
			        	unset($customer_detail[$key]);
			        }
			    }
			       // dd($customer_detail);
				 return $this->CommonController->successResponse($customer_detail,'Booking fetched Successfully',200);
	    	}
	    	else
	    	{
	    		return $this->CommonController->errorResponse('Data Not Found',201);
	    	}
	    }
	    else
	    {
	    	return $this->CommonController->errorResponse('saloon donot exist',201);
	    }

    }

 public function vendor_order_details(Request $request)
    {
       
         $customerBookings = DB::table('customer_booking')
            ->join('customers','customers.customer_id','=','customer_booking.customer_id')
            ->join('saloons', 'saloons.saloon_id', '=', 'customer_booking.saloon_id')
            ->join('saloon_slots', 'customer_booking.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
            ->select('customers.customer_id','customers.customer_name','customers.customer_mobile','customer_booking.customer_booking_id','customer_booking.customer_booking_code',
                'customer_booking.customer_booking_date', 'customer_booking.total_price',
                'customer_booking.total_price', 'customer_booking.booking_status_id',
                'customer_booking.saloon_id', 'saloons.saloon_name', 'saloons.saloon_address',
                'saloon_slots.saloon_slot_id', 'saloon_slots.slot_from', 'saloon_slots.slot_to','customer_booking.payment_type','customer_booking.payment_status_id')
            ->where('saloons.saloon_id', $request->saloon_id)->orderBy('customer_booking.customer_booking_id','desc');

        if ($request->booking_status_id == 3) {
            $customerBookings = $customerBookings
                ->whereIn('booking_status_id', [3,6]);
        } elseif ($request->booking_status_id == 10) {
            $customerBookings = $customerBookings
                ->where('booking_status_id', 10);
        } elseif ($request->booking_status_id == 2) {
            $customerBookings = $customerBookings
                ->where('booking_status_id', 2);
        }

        $customerBookings = $customerBookings->get();

        $currentTime = Carbon::now();
        
        foreach ($customerBookings as &$customerBooking) {
             $customerBooking->services = DB::table('booking_services')
              ->join('customer_booking','customer_booking.customer_booking_id','=','booking_services.customer_booking_id')
		      ->join('customers','customers.customer_id','=','customer_booking.customer_id')
              ->join('saloon_slots', 'booking_services.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
                ->join('saloons_services', 'booking_services.service_id', '=', 'saloons_services.saloon_service_id')
                ->join('services', 'saloons_services.service_name', '=', 'services.service_id')
                ->select(['customers.customer_name','customers.customer_id','saloons_services.saloon_service_id','services.service_id', 'services.service_name', 'saloons_services.other', 'saloon_slots.saloon_slot_id', 'saloon_slots.slot_from', 'saloon_slots.slot_to',
                	'customer_booking.customer_booking_id','customer_booking.payment_type','customer_booking.payment_status_id'])
                ->where('customer_booking.customer_booking_id', $customerBooking->customer_booking_id)->get();

         
        }
		// dd($customerBookings);
        return $this->CommonController->successResponse($customerBookings, 'Booking fetched Successfully', 200);
    }


	public function notifications_details(Request $request){
		$notifications=DB::table('vendor_notifications')->where('saloon_id',$request->saloon_id )->orderBy('id', 'DESC')->get();
		if(count($notifications)>0)
		{
		return $this->CommonController->successResponse($notifications,'data fetched successfully,200');
		}
		else{
			return $this->CommonControllers->errorResponse('not fetched',201);
		}

	}


	public function Vendor_booking_details(Request $request)
	{ 
	 $customerBookings = DB::table('customer_booking')
	//   ->Leftjoin('customer_rating','customer_rating.customer_booking_id','=','customer_booking.customer_booking_id')
	->join('customers','customer_booking.customer_id','=','customers.customer_id')
			 ->join('saloons', 'saloons.saloon_id', '=', 'customer_booking.saloon_id')
			 ->join('saloon_slots', 'customer_booking.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
			 ->select('customer_booking.customer_booking_id','customer_booking.update_status','customer_booking.customer_booking_code',
				 'customer_booking.customer_booking_date', 'customer_booking.total_price','customers.customer_name','customers.customer_mobile',
				 'customer_booking.total_price', 'customer_booking.booking_status_id','customer_booking.payment_type','customer_booking.payment_status_id','customer_booking.cancel_by','customer_booking.cancel_reason','customer_booking.saloon_id', 'saloons.saloon_name', 'saloons.owner_mobile', 'saloons.saloon_address',
				 'saloon_slots.saloon_slot_id', 'saloon_slots.slot_from', 'saloon_slots.slot_to')
			 ->where('customer_booking.customer_id', $request->customer_id)->where('customer_booking.customer_booking_id',$request->customer_booking_id);
			 $customerBookings = $customerBookings->get();
		 $currentTime = Carbon::now();
		//  dd($customerBookings);
		 foreach ($customerBookings as &$customerBooking) {
		 
			//  $current = Carbon::now();
			//  $bookingDateTime = Carbon::createFromFormat('d-m-Y h:i A', "{$customerBooking->customer_booking_date} {$customerBooking->slot_from}");
			//  $timeDifference=$bookingDateTime->diffInHours($current);
 
			//  $customerBooking->can_reschedule = ($timeDifference>=1 && $customerBooking->booking_status_id!=2 &&$customerBooking->update_status!=1 )?1:0;
			//  $customerBooking->can_cancelled = ($timeDifference>6 &&in_array($customerBooking->booking_status_id, [3,6]))?1:0;
			  $customerBooking->services = DB::table('booking_services')
    			  ->join('customer_booking','customer_booking.customer_booking_id','=','booking_services.customer_booking_id')
				  ->join('saloon_slots', 'booking_services.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
				 ->join('saloons_services', 'booking_services.service_id', '=', 'saloons_services.saloon_service_id')
				 ->join('services', 'saloons_services.service_name', '=', 'services.service_id')
				 ->select(['saloons_services.saloon_service_id','services.service_id', 'services.service_name', 'saloons_services.other','saloon_slots.slot_from', 'saloon_slots.slot_to'])
				 ->where('customer_booking.customer_booking_id', $customerBooking->customer_booking_id)->get();
 
			//  $customerBooking->images = DB::table('saloons_images')
			// 	 ->select(['saloons_images.saloon_image'])
			// 	 ->where('saloon_id', $customerBooking->saloon_id)->limit(1)->get();
 
		 }
 
		 return $this->CommonController->successResponse($customerBookings, 'Booking fetched Successfully', 200);
 
	}


	public function on_off_salon(Request $request)
    {$data=	DB::table('saloons')->where('saloon_id',$request->saloon_id)->get('on_status');
		if($request->status==$data[0]->on_status){
			return $this->CommonController->successResponse( 'Already exits', 200); 
		}else
		 DB::table('saloons')->where('saloon_id',$request->saloon_id)->update(['on_status'=>$request->status]);

        return $this->CommonController->successResponse( 'Status Updated Successfully', 200); 
    }

	public function show_on_off(Request $request){
	$data=	DB::table('saloons')->where('saloon_id',$request->saloon_id)->get('on_status');
	$data=$data[0]->on_status;
	if($data)return $this->CommonController->successResponse($data, 'Status Fetched Successfully', 200); 
	else return $this->CommonController->successResponse( 0,'Not Found', 201); 
	}


	public function vendor_local_data(Request $request){
		  $salon_details=DB::table('saloons')->where('saloon_id',$request->saloon_id)
                                                    ->where('saloon_status',0)->get();
               $saloon_id=$request->saloon_id;
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
                        $saloon_details=DB::table('saloons')->where('saloon_id',$request->saloon_id)
                                                    ->where('saloon_status',0)->get();
                        $saloon_details[0]->Services = DB::table('saloons_services')->where('saloon_id', $saloon_id)->get();
                        $saloon_details[0]->Images = DB::table('saloons_images')->where('saloon_id', $saloon_id)->get();
                        $saloon_details[0]->Slots = DB::table('saloon_slots')->where('saloon_id', $saloon_id)->get();
                         $saloon_details[0]->days = DB::table('days')->whereIn('day_id',$day)->get();
                          $saloon_details[0]->features = DB::table('features')->whereIn('feature_id',$features)
                          ->select('features.feature_id','features.feature_name')->get();

                       return $this->CommonController->successResponse($saloon_details,'Fetched Data)',200);
                  }
                    else
                    {

                           return $this->CommonController->errorResponse(['Not Valid'],201);
                      }
		
	}


    }

