<?php

namespace App\Http\Controllers;

use App\CustomerBookingModel;
use App\Http\Component\PaymentGateway;
use App\Http\Component\RazorGateway;
use Illuminate\Http\Request;
use Curl;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Component\FCMPush;
use GuzzleHttp\Middleware;
use Razorpay\Api\Api;
/**
 * @property CommonController CommonController
 */
class CustomerBookingController extends Controller
{
    public function __construct(Request $request)
    {
        $this->CommonController = new CommonController();

    }


    public function index()
    {
        $this->middleware('permission');
     $service_list=DB::table('services')->get();
     $booking_list=DB::table('customer_booking')->orderBy('customer_booking_id','desc')->get();

     foreach($booking_list as $key){
         $saloon_name= DB::table('saloons')->where('saloon_id',$key->saloon_id)->get('saloon_name');
         if(count($saloon_name)>0){$key->saloon_name=$saloon_name[0]->saloon_name;}
         else {$key->saloon_name="No Saloon Found";}

         $customer_data=DB::table('customers')->where('customer_id',$key->customer_id)->select('customer_name','customer_mobile')->get();
         if(count($customer_data)>0)
         {
             $key->customer_name=$customer_data[0]->customer_name;
            $key->customer_mobile=$customer_data[0]->customer_mobile;}
         else {$key->customer_name="No Saloon Found";
            $key->customer_mobile="No Saloon Found";}
         
     }
     foreach($booking_list as $key){
         if($key->booking_status_id==2)$key->booking_status_id='Cancelled';
         if($key->booking_status_id==3)$key->booking_status_id='Confirmed';
         if($key->booking_status_id==6)$key->booking_status_id='Pending';
         if($key->booking_status_id==10)$key->booking_status_id='Completed';
         if($key->booking_status_id==11)$key->booking_status_id='Refund';
         else if($key->booking_status_id==11)$key->booking_status_id='Refund';

     }

     
          $data = DB::table('customer_booking')
	                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
                        ->join('saloons_services','saloons_services.saloon_service_id','=','booking_services.service_id')
	                    ->join('services','saloons_services.service_name','=','services.service_id')
	                    ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
	                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id')
	                    ->get();
          //dd($data);
	         for($i = 0; $i < count($booking_list); $i++)
	            {
	                $abc = [];
	                for($j = 0; $j < count($data); $j++)
	                {
	                    if($booking_list[$i]->customer_booking_id == $data[$j]->customer_booking_id)
	                    {
	                        array_push($abc, $data[$j]);
	                    }
	                }
	                $booking_list[$i]->services = $abc;
                    // dd($booking_list[$i]->services);

	            }
             // $saloon_list=SaloonModel::all();
      return view('bookings.index',compact('booking_list','service_list'));
        
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
         $request->BookingDetails = $request->BookingDetails;
        $CustomerBookingId = DB::table('customer_booking')
            ->insertGetId(
                [
                    'customer_id' => $request->BookingDetails[0]['customer_id'],
                    'saloon_id' => $request->BookingDetails[0]['saloon_id'],
                    'customer_booking_date' => $request->BookingDetails[0]['customer_booking_date'],
                    'saloon_slot_id' => $request->BookingDetails[0]['saloon_slot_id'],
                    'total_price' => $request->BookingDetails[0]['total_price'],
                    'payment_type' => 'online'

                ]);
          
          // $ServicesData = $request->ServiceDetails;
        $ServicesData = $request->ServiceDetails;
        for ($i = 0; $i < count($ServicesData); $i++) {
            $service_detail[] = ['customer_booking_id' => $CustomerBookingId,
                'saloon_slot_id' => $request->BookingDetails[0]['saloon_slot_id'],
                'service_id' => $ServicesData[$i]['service_name'],
                'service_price' => $ServicesData[$i]['service_price']];
        }
        DB::table('booking_services')->insert($service_detail);

        $commission_rate=DB::table('saloons')->where('saloon_id',$request->BookingDetails[0]['saloon_id'])->select('commission_rate')->get();
        $commission_rate=$commission_rate[0]->commission_rate;
        $total_price=$request->BookingDetails[0]['total_price'];
        $amount_build=$total_price*$commission_rate/100;
        $settlement_amount=$total_price-$amount_build;
        $revenue_details=['customer_booking_id'=>$CustomerBookingId,
                            'saloon_id'=>$request->BookingDetails[0]['saloon_id'],
                            'total_price'=>$total_price,
                            'commission_rate'=>$commission_rate,
                            'amount_build'=>$amount_build,
                            'settlement_amount'=>$settlement_amount];
           
           DB::table('booking_revenues')->insert($revenue_details);
        

        //update customer  booking number
//        DB::table('customers')->where('customer_id', $customer_id)
//            ->update(['customer_total_booking' => DB::raw('customer_total_booking + 1')]);

        //customer booking  code
        $customer_booking_code = ($CustomerBookingId < 10) ? 'AL00' . $CustomerBookingId :
            (($CustomerBookingId >= 10 && $CustomerBookingId < 100) ? 'AL0' . $CustomerBookingId : 'AL' . $CustomerBookingId);
        DB::table('customer_booking')->where('customer_booking_id', $CustomerBookingId)
            ->update(['customer_booking_code' => $customer_booking_code]);

        //message to cutomer for booking  done
       $customer = DB::table('customers')->where('customer_id', $request->BookingDetails[0]['customer_id'])->get();
       $mobile_no = $customer[0]->customer_mobile;
       $name = $customer[0]->customer_name;
       $booking_date=$request->BookingDetails[0]['customer_booking_date'];
       $msg = "Dear $name,you slot has been booked on $booking_date.Thank You. ";
    //    Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91' . $mobile_no . '&sid=ONETIC&msg=' . urlencode($msg) . '&fl=0&gwid=2')->get();
//        $token_detail = DB::table('saloons')->select('saloons.device_id')->where('saloon_id',
//            $request->BookingDetails[0]['saloon_id'])->get();
//        $details[] = ['customer_name' => $customer[0]->customer_name,
//            'customer_mobile' => $customer[0]->customer_mobile];//
//
//        $this->CommonController->notification($token_detail[0]->device_id, 'New booking ',
//            'Customer new booking Notification');

        $customerBooking = DB::table('customer_booking')
            ->where('customer_booking_id', $CustomerBookingId)->first();
        $paymentGateway = new RazorGateway();
           
         return $this->CommonController->successResponse($paymentGateway->startTransaction($customerBooking), 'Booking Initiated Successfully', 200);
        
    }


    public function paymentResponse(Request $request)
    {
      
        $request->orderId;
        $request->orderAmount;
            
                    $orderId = $request->orderId;
                    $customerBooking = DB::table('customer_booking')
                        ->where('orderId', $orderId)->first();

                    if ($customerBooking->payment_status_id == 2) {
                        return $this->CommonController->successResponse(null, 'Booking Successfully', 200);
                    } else if ($customerBooking->total_price != $request->orderAmount) {
                        return $this->CommonController->errorResponse('Invalid amount', 200);
                    } else{
                               $dataToUpdate = [];
                                if ($request->txStatus == 'SUCCESS' or $request->txStatus == 'success' or $request->txStatus == 'Success') {
                                    $dataToUpdate['payment_status_id'] = 2;
                                    // $dataToUpdate['booking_status_id'] = 9;

                                } else {
                                    $dataToUpdate['payment_status_id'] = 3;
                                }
                                DB::table('customer_booking')->where('orderId', $request->orderId)
                                    ->update($dataToUpdate);
                                                                                    
                                if($request->txStatus == 'SUCCESS'||$request->txStatus == 'success'||$request->txStatus == 'Success')
                                {
                                    $data1=DB::table('saloons')->where('saloon_id', $customerBooking->saloon_id)->get();
                                    $data2=DB::table('customers')->where('customer_id', $customerBooking->customer_id)->get();
                                    
                                    if(!empty($data1[0]->device_id))
                                    {
                                        $payload = [
                                        'title' => "New Appointment has arrived",
                                        'description' => $data2[0]->customer_name.' has booked',
                                        'type' => 'payment',
                                        'user_id' => $data1[0]->saloon_id,
                                        'customer_id'=>$customerBooking->customer_id,
                                        'customer_booking_id'=>$customerBooking->customer_booking_id,
                                        'identifier' => 'order-123',
                                        ];
                                    
                                     FCMPush::sendPushToSingleDevice($payload, $data1[0]->device_id);
                                   }
                                   
                                   $customerBookingdata=DB::table('customer_booking')
                                   ->join('saloon_slots', 'customer_booking.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
                                   ->select('customer_booking.customer_booking_code','customer_booking.customer_booking_date','saloon_slots.slot_from', 'saloon_slots.slot_to','customer_booking.total_price',)
                                   ->where('customer_booking_id', $customerBooking->customer_booking_id)->first();

                                   $notifdetails=['customer_booking_code'=>$customerBookingdata->customer_booking_code,'description'=>$data2[0]->customer_name.' has booked','customer_booking_id'=>$customerBooking->customer_booking_id,'customer_id' =>$customerBooking->customer_id,'saloon_id' => $customerBooking->saloon_id,'total_price'=>$customerBookingdata->total_price,'datetime'=>$customerBookingdata->customer_booking_date,'slot_from'=>$customerBookingdata->slot_from,'slot_to'=>$customerBookingdata->slot_to];
                                
                                   DB::table('vendor_notifications')->insert($notifdetails);

                                   return $this->CommonController->successResponse(null, 'Status Updated', 200);
                                }else{ return $this->CommonController->successResponse(null,'status updated with failed payment',200);}
                        }

    }

    
    public function cancel_booking(Request $request)
    {
        $customer_detail = DB::table('customers')->where('customer_id', $request->customer_id)->get();
        if (count($customer_detail) > 0) 
        {
            $booking_detail = DB::table('customer_booking')
                                ->where('customer_booking_id', $request->customer_booking_id)->get();
            // $slot_details   = DB::table('saloon_slots')
            //                     ->where('saloon_slot_id', $booking_detail[0]->saloon_slot_id)->get();
            // $slot_from      = new DateTime($slot_details[0]->slot_from);
            // $cancel_time    = $slot_from->modify('-6 hours')->format("H:i:s");
            // 
    
           
            $schedule_date  = new DateTime($booking_detail[0]->customer_booking_date);
            $today_date     = new DateTime(date('d-m-Y ')); 
            $datetime = explode(" ",$booking_detail[0]->created_at);
            $timestamp = strtotime($datetime[1]) + 6*60*60;
            $cancel_time = date('H:i:s', $timestamp);
            $interval       = $today_date->diff($schedule_date);
            $current_time   = date('H:i:s');
            if (($interval->format('%a'))> 0 || $current_time <= $cancel_time) 
            { 
                //update  customer booking
                DB::table('customer_booking')
                            ->where('customer_booking_id', $request->customer_booking_id)
                            ->update(['cancel_by'=> $request->cancel_by,
                                       'cancel_reason'=> $request->cancel_reason,
                                       'booking_status_id'=> 2]);
            return $this->CommonController->successResponse(NULL, 'Booking cancelled Successfully', 200);
            } 
            else 
            {
                return $this->CommonController->errorResponse('Soory Booking Can Not be Cancelled ,Cancel time limit exceeded', 200);
            }
        } 
        else 
        {
            return $this->CommonController->errorResponse('Customer doesnot exist', 200);
        }

    }


    public function reschedule_booking(Request $request)
    {
        $customer_detail = DB::table('customers')->where('customer_id', $request->customer_id)->get();
        if (count($customer_detail) > 0)
         {
            $booking_details = DB::table('customer_booking')
                                  ->where('customer_booking_id', $request->customer_booking_id)->get();
            $slot_details   = DB::table('saloon_slots')
                                  ->where('saloon_slot_id', $booking_details[0]->saloon_slot_id)->get();
            //dd($slot_details);
            //tocheck if booking is already reschedule or not                      
            if ($booking_details[0]->update_status == 0) 
            {
                $schedule_date = new DateTime($booking_details[0]->customer_booking_date);
                $today_date = new DateTime(date('d-m-Y '));
                $interval = $today_date->diff($schedule_date);
                $current_slot = new DateTime($slot_details[0]->slot_from);
                $reschedule_time = $current_slot->modify('-1 hours')->format("h:i A");
                $current_time = date('h:i A');
                 // dd($slot_details[0]->slot_from.",".$current_time.",".$reschedule_time);
              // dd(strtotime($current_time) <= strtotime($reschedule_time));
                // dd($interval->format('%a'),$slot_details[0]->slot_from.",".$current_time.",".$reschedule_time);
                if (($interval->format('%a')) >= 7 || strtotime($current_time) <= strtotime($reschedule_time))
                 {
                    //update slot (booking_Service)
                    DB::table('booking_services')->where('customer_booking_id', $request->customer_booking_id)
                        ->update(['saloon_slot_id' => $request->updated_slot_id,]);
                    DB::table('customer_booking')->where('customer_booking_id', $request->customer_booking_id)
                            ->update(['customer_booking_date'=>$request->customer_booking_date,
                                      'saloon_slot_id' => $request->updated_slot_id,
                                       'booking_status_id'=>6, 
                                      'update_status' => 1]);
                    return $this->CommonController->successResponse(NULL, 'Booking rescheduled Successfully', 200);
                } 
                else 
                {
                    return $this->CommonController->errorResponse('Booking can not be rescheduled, as time limit exceeded ', 200);
                }
            } 
            else 
            {
                return $this->CommonController->errorResponse('Booking can not be rescheduled, alraedy rescheduled first', 200);
            }
        } 
        else
         {
            return $this->CommonController->errorResponse('customer does not exit', 200);
        }

    }


    function customer_favourite_salon(Request $request)
    {
        $result = DB::table('customer_fav_saloons')->where('customer_id', $request->customer_id)
            ->where('saloon_id', $request->saloon_id)->get();
        if (count($result) > 0) {
            return $this->CommonController->successResponse(null, 'Saloon already exist in your favourite list', 200);
        } else {
            $data = ['customer_id' => $request->customer_id,
                'saloon_id' => $request->saloon_id,
            ];
            DB::table('customer_fav_saloons')->insert($data);
            return $this->CommonController->successResponse(null, 'Data Saved succesfuly', 200);
        }
    }

    function unfavourite_salon(Request $request)
    {
        DB::table('customer_fav_saloons')
            ->where('customer_id', $request->customer_id)
            ->where('saloon_id', $request->saloon_id)->delete();
        return $this->CommonController->successResponse(null, 'Data Delete succesfully', 200);
    }

    function customer_payment_history($customer_id)
    {
        $data['payment_history'] = DB::table('customer_booking')
            ->join('saloons', 'saloons.saloon_id', '=', 'customer_booking.saloon_id')
            ->join('saloon_slots', 'saloon_slots.saloon_slot_id', '=', 'customer_booking.saloon_slot_id')
            ->join('payment_status', 'payment_status.payment_status_id', '=', 'customer_booking.payment_status_id')
            ->select('customer_booking.customer_booking_id as order_id', 'customer_booking.customer_booking_id', 'customer_booking.customer_booking_code', 'customer_booking.customer_id', 'customer_booking.total_price','customer_booking.payment_type',
                'saloons.saloon_name', 'saloon_slots.slot_from', 'saloon_slots.slot_to', 'payment_status.payment_status', 'customer_booking.customer_booking_date', 'customer_booking.created_at as payment_date')
            ->orderBy('customer_booking.customer_booking_id', 'desc')
            ->where('customer_id', $customer_id)->get();

        $data['booking_services'] = DB::table('customer_booking')
            ->join('booking_services', 'booking_services.customer_booking_id', '=', 'customer_booking.customer_booking_id')
            ->join('saloons_services', 'saloons_services.saloon_service_id', '=', 'booking_services.service_id')
            ->Join('services', 'services.service_id', '=', 'saloons_services.saloon_service_id')
            ->select('booking_services.customer_booking_id', 'booking_services.service_price', 'saloons_services.saloon_service_id', 'services.service_name')
            ->get();


        for ($i = 0; $i < count($data['payment_history']); $i++) {
            $abc = [];
            for ($j = 0; $j < count($data['booking_services']); $j++) {
                if ($data['payment_history'][$i]->customer_booking_id == $data['booking_services'][$j]->customer_booking_id) {
                    array_push($abc, $data['booking_services'][$j]);
                }
            }
            $data['payment_history'][$i]->booking_services = $abc;
        }
        return $this->CommonController->successResponse($data['payment_history'], 'Data fetched succesfully', 200);
    }

    function customer_fav_salon(Request $request, $customer_id)
    {
        $data['saloons'] = DB::table('customer_fav_saloons')
            ->join('saloons', 'saloons.saloon_id', '=', 'customer_fav_saloons.saloon_id')
            ->select('customer_fav_saloons.*', 'saloons.*')
            ->where('customer_id', $customer_id)->get();
        $data['saloon_images'] = DB::table('customer_fav_saloons')
            ->join('saloons_images', 'saloons_images.saloon_id', '=', 'customer_fav_saloons.saloon_id')
            ->select('saloons_images.saloon_image', 'saloons_images.saloon_id')
            ->get();
        for ($i = 0; $i < count($data['saloons']); $i++) {
            $abc = [];
            for ($j = 0; $j < count($data['saloon_images']); $j++) {
                if ($data['saloons'][$i]->saloon_id == $data['saloon_images'][$j]->saloon_id) {
                    array_push($abc, $data['saloon_images'][$j]);
                }
            }
            $data['saloons'][$i]->saloon_images = $abc;
        }
        $distance = [];
        for ($k = 0; $k < count($data['saloons']); $k++) {
            $saloon_lat = $data['saloons'][$k]->saloon_lattitude;
            $saloon_long = $data['saloons'][$k]->saloon_longitude;
            $customer_lat = $request->customer_lattitude;
            $customer_log = $request->customer_longitude;
            $theta = $customer_lat - $saloon_lat;
            $dist = sin(deg2rad($customer_lat)) * sin(deg2rad($saloon_lat)) + cos(deg2rad($customer_lat)) * cos(deg2rad($saloon_lat)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper('K');
            $data['saloons'][$k]->distance = round($miles * 1.609344 * 100) / 100;
        }
        return $this->CommonController->successResponse($data['saloons'], 'Data fetched succesfuly', 200);

    }


    function customer_order_details(Request $request)
    {
        $customer = DB::table('customers')->where('customer_id', $request->customer_id)->get();
        if (count($customer) > 0) {
            $query1 = DB::table('customer_booking')
                ->join('saloons', 'saloons.saloon_id', '=', 'customer_booking.saloon_id')
                //->join('booking_services', 'booking_services.customer_booking_id', '=', 'customer_booking.customer_booking_id')
                ->join('saloon_slots', 'customer_booking.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
                ->select('customer_booking.customer_booking_code', 'customer_booking.customer_booking_date', 'customer_booking.total_price','saloons.saloon_id','saloons.saloon_name', 'saloons.saloon_name', 'saloons.saloon_address', 'customer_booking.customer_booking_id', 'customer_booking.booking_status_id', 'saloon_slots.saloon_slot_id', 'saloon_slots.slot_to', 'saloon_slots.slot_from')
                ->where('customer_id', $request->customer_id);

            $query = DB::table('customer_booking')
                ->join('booking_services', 'booking_services.customer_booking_id', '=', 'customer_booking.customer_booking_id')
                ->join('customers', 'customers.customer_id', '=', 'customer_booking.customer_id')
                ->leftJoin('services', 'booking_services.service_id', '=', 'services.service_id')
                ->join('saloon_slots', 'booking_services.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
                ->select('services.service_id', 'services.service_name', 'booking_services.service_price', 'saloon_slots.saloon_slot_id', 'saloon_slots.slot_to', 'saloon_slots.slot_from', 'booking_services.customer_booking_id', 'customer_booking.customer_id')
                ->where('customer_booking.customer_id', $request->customer_id);
            //for cancelled booking
            if ($request->booking_status_id == 2) {
                $query1->where('customer_booking.booking_status_id', $request->booking_status_id);
                $data = $query1->get();
            } //for approved or upcoming order
            elseif ($request->booking_status_id == 3) {
                //->where([[\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '>=', date("Y-m-d")]  ])
                $query1->whereIn('booking_status_id', [$request->booking_status_id, 6, 10]);
                $query->whereIn('customer_booking.booking_status_id', [$request->booking_status_id, 6, 10]);
                //->where([[\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '>=', date("Y-m-d")] ]);
                $data = $query->get();
                // foreach ($data as $key => $slot)
                // {
                //     if(strtotime($slot->slot_from) < strtotime(date('h:i A')))
                //     {
                //         unset($data[$key]);
                //     }
                // }
            } //for completed
            elseif ($request->booking_status_id == 9) {
                $query1->where([
                    [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '<=', date("Y-m-d")]
                ])
                    ->where('booking_status_id', $request->booking_status_id);

                $query->where('customer_booking.booking_status_id', $request->booking_status_id)
                    ->where([
                        [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '<=', date("Y-m-d")]
                    ]);
                $data = $query->get();
                foreach ($data as $key => $slot) {
                    if (strtotime($slot->slot_from) < strtotime(date('h:i A'))) {
                        unset($data[$key]);
                    }
                }
            } //for  delayed
            elseif ($request->booking_status_id == 10) {
                $query1->where('booking_status_id', 3)
                    ->where([
                        [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '<=', date("Y-m-d")]]);
                $query->where('customer_booking.booking_status_id', 3)
                    ->where([
                        [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '<=', date("Y-m-d")]]);
                $data = $query->get()->toArray();
                foreach ($data as $key => $slot) {
                    if (strtotime($slot->slot_to) > strtotime(date('h:i A')) && strtotime($slot->customer_booking_date) >= strtotime(date('q-d-Y'))) {
                        //dd($key);
                        unset($data[$key]);
                    }
                }
            } else {
                return $this->CommonController->errorResponse('no orders', 200);
            }

            $customer_detail = $query1->get();

            if (count($customer_detail) > 0 && count($data) > 0) {
                foreach ($customer_detail as $key => $dt) {
                    $abc = [];
                    foreach ($data as $key1) {
                        if ($dt->customer_booking_id == $key1->customer_booking_id) {
                            array_push($abc, $key1);
                        }
                    }
                    if (count($abc) > 0) {
                        $dt->services = $abc;
                    } else {
                        unset($customer_detail[$key]);
                    }
                }
                
                $saloon_images = DB::table('customer_booking')
                                ->join('saloons_images', 'saloons_images.saloon_id', '=', 'customer_booking.saloon_id')
                                ->select('saloons_images.saloon_image','saloons_images.saloon_id')
                                ->where('customer_booking.customer_id', $request->customer_id)
                                ->get();
                for($i = 0; $i < count($customer_detail); $i++)
                {
                    $abc = [];
                    for($j = 0; $j < count($saloon_images); $j++)
                    {
                        if($customer_detail[$i]->saloon_id == $saloon_images[$j]->saloon_id)
                        {
                            array_push($abc, $saloon_images[$j]);
                        }
                    }
                    $customer_detail[$i]->saloon_images = $abc;
                }
                return $this->CommonController->successResponse($customer_detail, 'Booking fetched Successfully', 200);
            } else {
                return $this->CommonController->errorResponse('Data Not Found', 201);
            }
        } else {
            return $this->CommonController->errorResponse('customer donot exist', 201);
        }

    }

    public function myAppointments(Request $request)
    {
        $customerBookings = DB::table('customer_booking')
           ->Leftjoin('customer_rating','customer_rating.customer_booking_id','=','customer_booking.customer_booking_id')
            ->join('saloons', 'saloons.saloon_id', '=', 'customer_booking.saloon_id')
            ->join('saloon_slots', 'customer_booking.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
            ->select('customer_booking.customer_booking_id','customer_booking.update_status','customer_booking.customer_booking_code','customer_booking.cancel_by','customer_booking.cancel_reason','customer_rating.customer_rating',
                'customer_booking.customer_booking_date', 'customer_booking.total_price',
                'customer_booking.total_price', 'customer_booking.booking_status_id','customer_booking.payment_type',
                'customer_booking.saloon_id', 'saloons.saloon_name', 'saloons.saloon_address',
                'saloon_slots.saloon_slot_id', 'saloon_slots.slot_from', 'saloon_slots.slot_to')
            ->where('customer_booking.customer_id', $request->customer_id)->whereIn('customer_booking.payment_status_id',[2,6])->orderBy('customer_booking.customer_booking_id','desc');

            // ->where( 'customer_booking.payment_status_id', 2);
         
        $request->type = strtolower($request->type);
        if ($request->type == 'upcoming') {
            $customerBookings = $customerBookings
                ->whereIn('booking_status_id', [3,6]);
        } elseif ($request->type == 'completed') {
            $customerBookings = $customerBookings
                ->where('booking_status_id', 10);
        } elseif ($request->type == 'cancelled') {
            $customerBookings = $customerBookings
                ->where('booking_status_id', 2);
        }

        $customerBookings = $customerBookings->get();
        $currentTime = Carbon::now();
        
        foreach ($customerBookings as &$customerBooking) {
        
            $current = Carbon::now();
            $bookingDateTime = Carbon::createFromFormat('d-m-Y h:i A', "{$customerBooking->customer_booking_date} {$customerBooking->slot_from}");
            $timeDifference=$bookingDateTime->diffInHours($current);

            $customerBooking->can_reschedule = ($timeDifference>=1 && $customerBooking->booking_status_id!=2 &&$customerBooking->update_status!=1 )?1:0;
            $customerBooking->can_cancelled = ($timeDifference>6 &&in_array($customerBooking->booking_status_id, [3,6]))?1:0;
             $customerBooking->booking_services = DB::table('booking_services')
                ->join('saloons_services', 'booking_services.service_id', '=', 'saloons_services.saloon_service_id')
                ->join('services', 'saloons_services.service_name', '=', 'services.service_id')
                ->select(['saloons_services.saloon_service_id','services.service_id', 'services.service_name', 'saloons_services.other'])
                ->where('customer_booking_id', $customerBooking->customer_booking_id)->get();

            $customerBooking->images = DB::table('saloons_images')
                ->select(['saloons_images.saloon_image'])
                ->where('saloon_id', $customerBooking->saloon_id)->limit(1)->get();

        }

        return $this->CommonController->successResponse($customerBookings, 'Booking fetched Successfully', 200);
    }


   

    public function customerBookingByCash(Request $request)
    {

          $request->BookingDetails =$request->BookingDetails;
          $CustomerBookingId = DB::table('customer_booking')
            ->insertGetId(
                [
                    'customer_id' => $request->BookingDetails[0]['customer_id'],
                    'saloon_id' => $request->BookingDetails[0]['saloon_id'],
                    'customer_booking_date' => $request->BookingDetails[0]['customer_booking_date'],
                    'saloon_slot_id' => $request->BookingDetails[0]['saloon_slot_id'],
                    'total_price' => $request->BookingDetails[0]['total_price'],
                    'payment_type'=>"cash"

                ]);
          
         // $ServicesData = $request->ServiceDetails;
         $ServicesData = $request->ServiceDetails;
        for ($i = 0; $i < count($ServicesData); $i++) {
            $service_detail[] = ['customer_booking_id' => $CustomerBookingId,
                'saloon_slot_id' => $request->BookingDetails[0]['saloon_slot_id'],
                'service_id' => $ServicesData[$i]['service_name'],
                'service_price' => $ServicesData[$i]['service_price']];
        }
        DB::table('booking_services')->insert($service_detail);
        
        $commission_rate=DB::table('saloons')->where('saloon_id',$request->BookingDetails[0]['saloon_id'])->select('commission_rate')->get();
        $commission_rate=$commission_rate[0]->commission_rate;
        $total_price=$request->BookingDetails[0]['total_price'];
        $amount_build=$total_price*$commission_rate/100;
        $settlement_amount=$amount_build;
        $revenue_details=['customer_booking_id'=>$CustomerBookingId,
                            'saloon_id'=>$request->BookingDetails[0]['saloon_id'],
                            'total_price'=>$total_price,
                            'commission_rate'=>$commission_rate,
                            'amount_build'=>$amount_build,
                            'settlement_amount'=>$settlement_amount];
           
           DB::table('booking_revenues')->insert($revenue_details);

             //customer booking  code
        $customer_booking_code = ($CustomerBookingId < 10) ? 'AL00' . $CustomerBookingId :
            (($CustomerBookingId >= 10 && $CustomerBookingId < 100) ? 'AL0' . $CustomerBookingId : 'AL' . $CustomerBookingId);
        DB::table('customer_booking')->where('customer_booking_id', $CustomerBookingId)
            ->update(['customer_booking_code' => $customer_booking_code]);

        //message to cutomer for booking  done
       $customer = DB::table('customers')->where('customer_id', $request->BookingDetails[0]['customer_id'])->get();
       $mobile_no = $customer[0]->customer_mobile;
       
       $name = $customer[0]->customer_name;
       $booking_date=$request->BookingDetails[0]['customer_booking_date'];
       $msg = "Dear $name,you slot has been booked on $booking_date.Thank You. ";
       Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91' . $mobile_no . '&sid=ONETIC&msg=' . urlencode($msg) . '&fl=0&gwid=2')->get();
       // $token_detail = DB::table('saloons')->select('saloons.device_id')->where('saloon_id',
       //     $request->BookingDetails[0]['saloon_id'])->get();
       // $details[] = ['customer_name' => $customer[0]->customer_name,
       //     'customer_mobile' => $customer[0]->customer_mobile];


       // $this->CommonController->notification($token_detail[0]->device_id, 'New booking ',
       //     'Customer new booking Notification');

        $customerBooking = DB::table('customer_booking')
        ->join('saloon_slots', 'customer_booking.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
        ->select('customer_booking.customer_booking_code','customer_booking.customer_booking_date','saloon_slots.slot_from', 'saloon_slots.slot_to','customer_booking.total_price',)->where('customer_booking_id', $CustomerBookingId)->first();
        
        
            // dd($customerBooking->total_price);
               $data1=DB::table('saloons')->where('saloon_id', $request->BookingDetails[0]['saloon_id'])->get();
                
              
            
                $payload = [
                    'title' => "New Appointment has arrived",
                    'description' => $name.' has booked',
                    'type' => 'payment',
                    'customer_id'=>$request->BookingDetails[0]['customer_id'],
                    'customer_booking_id'=>$CustomerBookingId,
                    'user_id' => $data1[0]->saloon_id,
                    'identifier' => 'order-123',
                ];
                 if(!empty($data1[0]->device_id))
                {
                    FCMPush::sendPushToSingleDevice($payload, $data1[0]->device_id);
               }
               $notifdetails=['customer_booking_code'=>$customer_booking_code,'description'=>$name.' has booked','customer_booking_id'=>$CustomerBookingId,'customer_id' =>$request->BookingDetails[0]['customer_id'],'saloon_id' => $request->BookingDetails[0]['saloon_id'],'total_price'=>$customerBooking->total_price,'datetime'=>$customerBooking->customer_booking_date,'slot_from'=>$customerBooking->slot_from,'slot_to'=>$customerBooking->slot_to];
               //rishi
              DB::table('vendor_notifications')->insert($notifdetails);

        return $this->CommonController->successResponse( null,'Your Booking Done Successfully', 200);
    }

   public function booking_details(Request $request)
   {
    $customerBookings = DB::table('customer_booking')
     ->Leftjoin('customer_rating','customer_rating.customer_booking_id','=','customer_booking.customer_booking_id')
            ->join('saloons', 'saloons.saloon_id', '=', 'customer_booking.saloon_id')
            ->join('saloon_slots', 'customer_booking.saloon_slot_id', '=', 'saloon_slots.saloon_slot_id')
            ->select('customer_booking.customer_booking_id','customer_booking.update_status','customer_booking.customer_booking_code','customer_rating.customer_rating',
                'customer_booking.customer_booking_date', 'customer_booking.total_price',
                'customer_booking.total_price', 'customer_booking.booking_status_id','customer_booking.payment_type','customer_booking.cancel_by','customer_booking.cancel_reason','customer_booking.saloon_id', 'saloons.saloon_name', 'saloons.owner_mobile', 'saloons.saloon_address','saloons.saloon_longitude','saloons.saloon_lattitude',
                'saloon_slots.saloon_slot_id', 'saloon_slots.slot_from', 'saloon_slots.slot_to')
            ->where('customer_booking.customer_id', $request->customer_id)->where('customer_booking.customer_booking_id',$request->customer_booking_id);
            $customerBookings = $customerBookings->get();
        $currentTime = Carbon::now();
        
        foreach ($customerBookings as &$customerBooking) {
        
            $current = Carbon::now();
            $bookingDateTime = Carbon::createFromFormat('d-m-Y h:i A', "{$customerBooking->customer_booking_date} {$customerBooking->slot_from}");
            $timeDifference=$bookingDateTime->diffInHours($current);

            $customerBooking->can_reschedule = ($timeDifference>=1 && $customerBooking->booking_status_id!=2 && $customerBooking->booking_status_id!=9 &&$customerBooking->update_status!=1 )?1:0;
            $customerBooking->can_cancelled = ($timeDifference>6 &&in_array($customerBooking->booking_status_id, [3,6]))?1:0;
             $customerBooking->booking_services = DB::table('booking_services')
                ->join('saloons_services', 'booking_services.service_id', '=', 'saloons_services.saloon_service_id')
                ->join('services', 'saloons_services.service_name', '=', 'services.service_id')
                ->select(['saloons_services.saloon_service_id','services.service_id', 'services.service_name', 'saloons_services.other'])
                ->where('customer_booking_id', $customerBooking->customer_booking_id)->get();

            $customerBooking->images = DB::table('saloons_images')
                ->select(['saloons_images.saloon_image'])
                ->where('saloon_id', $customerBooking->saloon_id)->limit(1)->get();

        }

        return $this->CommonController->successResponse($customerBookings, 'Booking fetched Successfully', 200);

   }

   public function notifications_details(Request $request){
    $notifications=DB::table('customer_notifications')->where('customer_id',$request->customer_id )->orderBy('id', 'DESC')->get();
    if(count($notifications)>0)
    {
    return $this->CommonController->successResponse($notifications,'data fetched successfully,200');
    }
    else{
        return $this->CommonControllers->errorResponse('not fetched',201);
    }

}

public function rescheduled()
{
    $this->middleware('permission');
 $service_list=DB::table('services')->get();
 $booking_list=DB::table('customer_booking')->where('update_status',1)->orderBy('customer_booking_id','desc')->get();

 $todaybooking_list=DB::table('customer_booking')->where('update_status',1)->wheredate('created_at',date("Y-m-d"))->orderBy('customer_booking_id','desc')->get();
 $count_bookings=count($todaybooking_list);


 foreach($booking_list as $key){
     $saloon_name= DB::table('saloons')->where('saloon_id',$key->saloon_id)->get('saloon_name');
     if(count($saloon_name)>0){$key->saloon_name=$saloon_name[0]->saloon_name;}
     else {$key->saloon_name="No Saloon Found";}
     $customer_data=DB::table('customers')->where('customer_id',$key->customer_id)->select('customer_name','customer_mobile')->get();
     if(count($customer_data)>0){$key->customer_name=$customer_data[0]->customer_name;
        $key->customer_mobile=$customer_data[0]->customer_mobile;}
     else {$key->customer_name="No Saloon Found";
        $key->customer_mobile="No Saloon Found";}
     
 }
 foreach($booking_list as $key){
     if($key->booking_status_id==2)$key->booking_status_id='Cancelled';
     if($key->booking_status_id==3)$key->booking_status_id='Confirmed';
     if($key->booking_status_id==6)$key->booking_status_id='Pending';
     if($key->booking_status_id==9)$key->booking_status_id='Completed';
     if($key->booking_status_id==10)$key->booking_status_id='Delayed';
     else if($key->booking_status_id==11)$key->booking_status_id='Refund';

 }


      $data = DB::table('customer_booking')
                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
                    ->join('saloons_services','saloons_services.saloon_service_id','=','booking_services.service_id')
                    ->join('services','saloons_services.service_name','=','services.service_id')
                    ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id')
                    ->get();
      //dd($data);
         for($i = 0; $i < count($booking_list); $i++)
            {
                $abc = [];
                for($j = 0; $j < count($data); $j++)
                {
                    if($booking_list[$i]->customer_booking_id == $data[$j]->customer_booking_id)
                    {
                        array_push($abc, $data[$j]);
                    }
                }
                $booking_list[$i]->services = $abc;
                // dd($booking_list[$i]->services);

            }
         // $saloon_list=SaloonModel::all();
  return view('bookings.rescheduled',compact('booking_list','service_list','count_bookings'));
    
}
public function today_rescheduled()
{
    $this->middleware('permission');
 $service_list=DB::table('services')->get();
 $booking_list=DB::table('customer_booking')->where('update_status',1)->wheredate('created_at',date("Y-m-d"))->orderBy('customer_booking_id','desc')->get();



 foreach($booking_list as $key){
     $saloon_name= DB::table('saloons')->where('saloon_id',$key->saloon_id)->get('saloon_name');
     if(count($saloon_name)>0){$key->saloon_name=$saloon_name[0]->saloon_name;}
     else {$key->saloon_name="No Saloon Found";}
     $customer_data=DB::table('customers')->where('customer_id',$key->customer_id)->select('customer_name','customer_mobile')->get();
     if(count($customer_data)>0){$key->customer_name=$customer_data[0]->customer_name;
        $key->customer_mobile=$customer_data[0]->customer_mobile;}
     else {$key->customer_name="No Saloon Found";
        $key->customer_mobile="No Saloon Found";}
     
 }
 foreach($booking_list as $key){
     if($key->booking_status_id==2)$key->booking_status_id='Cancelled';
     if($key->booking_status_id==3)$key->booking_status_id='Confirmed';
     if($key->booking_status_id==6)$key->booking_status_id='Pending';
     if($key->booking_status_id==9)$key->booking_status_id='Completed';
     if($key->booking_status_id==10)$key->booking_status_id='Delayed';
     else if($key->booking_status_id==11)$key->booking_status_id='Refund';

 }


      $data = DB::table('customer_booking')
                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
                    ->join('saloons_services','saloons_services.saloon_service_id','=','booking_services.service_id')
                    ->join('services','saloons_services.service_name','=','services.service_id')
                    ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id')
                    ->get();
      //dd($data);
         for($i = 0; $i < count($booking_list); $i++)
            {
                $abc = [];
                for($j = 0; $j < count($data); $j++)
                {
                    if($booking_list[$i]->customer_booking_id == $data[$j]->customer_booking_id)
                    {
                        array_push($abc, $data[$j]);
                    }
                }
                $booking_list[$i]->services = $abc;
                // dd($booking_list[$i]->services);

            }
         // $saloon_list=SaloonModel::all();
  return view('bookings.todayrescheduled',compact('booking_list','service_list'));
    
}

public function cancelled()
{
    $this->middleware('permission');
 $service_list=DB::table('services')->get();
 $booking_list=DB::table('customer_booking')->where('booking_status_id',2)->orderBy('customer_booking_id','desc')->get();

 $todaybooking_list=DB::table('customer_booking')->where('booking_status_id',2)->wheredate('created_at',date("Y-m-d"))->orderBy('customer_booking_id','desc')->get();
 $count_bookings=count($todaybooking_list);

 foreach($booking_list as $key){
     $saloon_name= DB::table('saloons')->where('saloon_id',$key->saloon_id)->get('saloon_name');
     if(count($saloon_name)>0){$key->saloon_name=$saloon_name[0]->saloon_name;}
     else {$key->saloon_name="No Saloon Found";}
     $customer_data=DB::table('customers')->where('customer_id',$key->customer_id)->select('customer_name','customer_mobile')->get();
     if(count($customer_data)>0){$key->customer_name=$customer_data[0]->customer_name;
        $key->customer_mobile=$customer_data[0]->customer_mobile;}
     else {$key->customer_name="No Saloon Found";
        $key->customer_mobile="No Saloon Found";}
     
 }
 foreach($booking_list as $key){
     if($key->booking_status_id==2)$key->booking_status_id='Cancelled';
     if($key->booking_status_id==3)$key->booking_status_id='Confirmed';
     if($key->booking_status_id==6)$key->booking_status_id='Pending';
     if($key->booking_status_id==9)$key->booking_status_id='Completed';
     if($key->booking_status_id==10)$key->booking_status_id='Delayed';
     else if($key->booking_status_id==11)$key->booking_status_id='Refund';

 }

      $data = DB::table('customer_booking')
                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
                    ->join('saloons_services','saloons_services.saloon_service_id','=','booking_services.service_id')
                    ->join('services','saloons_services.service_name','=','services.service_id')
                    ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id')
                    ->get();
      //dd($data);
         for($i = 0; $i < count($booking_list); $i++)
            {
                $abc = [];
                for($j = 0; $j < count($data); $j++)
                {
                    if($booking_list[$i]->customer_booking_id == $data[$j]->customer_booking_id)
                    {
                        array_push($abc, $data[$j]);
                    }
                }
                $booking_list[$i]->services = $abc;
                // dd($booking_list[$i]->services);

            }
         // $saloon_list=SaloonModel::all();
  return view('bookings.cancelled',compact('booking_list','service_list','count_bookings'));
    
}

public function today_cancelled()
{
    $this->middleware('permission');
 $service_list=DB::table('services')->get();
 $booking_list=DB::table('customer_booking')->where('booking_status_id',2)->wheredate('created_at',date("Y-m-d"))->orderBy('customer_booking_id','desc')->get();
 $count_bookings=count($booking_list);
 

 foreach($booking_list as $key){
     $saloon_name= DB::table('saloons')->where('saloon_id',$key->saloon_id)->get('saloon_name');
     if(count($saloon_name)>0){$key->saloon_name=$saloon_name[0]->saloon_name;}
     else {$key->saloon_name="No Saloon Found";}
     $customer_data=DB::table('customers')->where('customer_id',$key->customer_id)->select('customer_name','customer_mobile')->get();
     if(count($customer_data)>0){$key->customer_name=$customer_data[0]->customer_name;
        $key->customer_mobile=$customer_data[0]->customer_mobile;}
     else {$key->customer_name="No Saloon Found";
        $key->customer_mobile="No Saloon Found";}
     
 }
 foreach($booking_list as $key){
     if($key->booking_status_id==2)$key->booking_status_id='Cancelled';
     if($key->booking_status_id==3)$key->booking_status_id='Confirmed';
     if($key->booking_status_id==6)$key->booking_status_id='Pending';
     if($key->booking_status_id==9)$key->booking_status_id='Completed';
     if($key->booking_status_id==10)$key->booking_status_id='Delayed';
     else if($key->booking_status_id==11)$key->booking_status_id='Refund';

 }

      $data = DB::table('customer_booking')
                    ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
                    ->join('saloons_services','saloons_services.saloon_service_id','=','booking_services.service_id')
                    ->join('services','saloons_services.service_name','=','services.service_id')
                    ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
                    ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id')
                    ->get();
      //dd($data);
         for($i = 0; $i < count($booking_list); $i++)
            {
                $abc = [];
                for($j = 0; $j < count($data); $j++)
                {
                    if($booking_list[$i]->customer_booking_id == $data[$j]->customer_booking_id)
                    {
                        array_push($abc, $data[$j]);
                    }
                }
                $booking_list[$i]->services = $abc;
                // dd($booking_list[$i]->services);

            }
         // $saloon_list=SaloonModel::all();
  return view('bookings.todaycancelled',compact('booking_list','service_list'));
    
}

  
}
