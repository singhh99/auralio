<?php


namespace App\Http\Controllers;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Http\Request;
use DB;
use DateTime;

class VendorController extends Controller
{
    public function index($saloon_id,$id)
    {
            $status=DB::table('booking_statuses') ->select('booking_status_name')->where('booking_status_id',$id)->get();
             $status=$status[0]->booking_status_name;
            $query1 = DB::table('customer_booking')
                                ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
                                ->join('customers','customers.customer_id','=','customer_booking.customer_id')
                                ->select('customers.customer_name','customer_booking.customer_booking_code','customers.customer_mobile','customers.customer_code','customer_booking.customer_booking_id','customer_booking.total_price','saloon_slots.slot_from','saloon_slots.slot_to','customer_booking.booking_status_id','customer_booking.cancel_by','customer_booking.cancel_reason','customer_booking.payment_type' )
                               ->where('customer_booking.saloon_id', $saloon_id)->orderBy('customer_booking.customer_booking_code','DESC');
            //dd($query1);
            $query = DB::table('customer_booking')
                            ->join('booking_services','booking_services.customer_booking_id','=','customer_booking.customer_booking_id')
                            ->join('customers','customers.customer_id','=','customer_booking.customer_id')
                            ->join('saloons_services','booking_services.service_id','=','saloons_services.saloon_service_id')
                            ->join('services','services.service_id','=','saloons_services.service_name')
                            ->join('saloon_slots','booking_services.saloon_slot_id','=','saloon_slots.saloon_slot_id')
                            ->select('services.service_id','services.service_name','booking_services.service_price','saloon_slots.saloon_slot_id', 'saloon_slots.slot_to','saloon_slots.slot_from','booking_services.customer_booking_id','customer_booking.customer_id', 'customer_booking.customer_booking_date','customers.customer_name')
                            ->where('customer_booking.saloon_id', $saloon_id);

             //for cancelled booking
            if($id==2)
            {
                 $query1->where('customer_booking.booking_status_id', $id);
                 $data = $query->get();
                 

            }
            //for approved or upcoming order
            elseif($id==3)
            {
               $query1->where([
                              [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '>=', date("Y-m-d")]
                            ])
                      ->where('booking_status_id',$id);

               $query->where('customer_booking.booking_status_id', $id)
                     ->where([
                              [\DB::raw("STR_TO_DATE(customer_booking.customer_booking_date,'%d-%m-%Y')"), '>=', date("Y-m-d")]
                            ]);
                $data = $query->get();
                foreach ($data as $key => $slot)
                {
                    if(strtotime($slot->slot_from) > strtotime(date('h:i A')))
                    {
                        unset($data[$key]);
                    }
                }

            }
            //for  delayed
            elseif($id==10)
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
                    if(strtotime($slot->slot_to) < strtotime(date('h:i A')) && strtotime($slot->customer_booking_date) <= strtotime(date('q-d-Y')))
                    {
                        //dd($key);
                        unset($data[$key]);
                    }
                }
            }
            else
            {
                 return view('vendor_orders.index');
             // return $this->CommonController->errorResponse('no orders',200);
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
                
                 return view('vendor_orders.index',compact('customer_detail','status','id'));
            }else

            {
                return view('vendor_orders.index2');
            }
        }

    //


    public function notification () {
     
        $this->mydata();

    }

    public function mydata(){
        $url = "https://fcm.googleapis.com/fcm/send";
        $token = "cCxL5Mj3GbU:APA91bH56I-Rn1wXJ4P6itFIeMkcT7TdWDypkHytzf_EDZrn2TDN-QpywqGRY5LxQ4oc4O4WxBejXCU_wRU5OQqq6h11HtNjsrMNgBg9L73twue-LsC3kvGJbS1lY_b2OCBt1p0LxCtC";
        $serverKey = 'AAAAuWzx30A:APA91bFGw3scKULtJMWsBNa4bY5Vu9wKNFCFWWL8j5QHRqow7GxcBztdpm_XWuv52caaS1aXYIN7A3r__Zqi3Zj2liTovr8SUf8mEskuHGjhQMIRILu06VmhqYpqynoB4r9iIkFe01XC';
        $title = "Notification title";
        $body = "Hello I am from Your php server";
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


    public function order_detials($customer_booking_id)
    {
    $data = DB::table('customer_booking')
                        ->join('saloon_slots','customer_booking.saloon_slot_id','=','saloon_slots.saloon_slot_id')
                         ->join('saloons','customer_booking.saloon_id','=','saloons.saloon_id')
                         ->join('customers','customers.customer_id','=','customer_booking.customer_id')
                        ->select('customers.customer_name','customer_booking.customer_booking_code','customers.customer_mobile','customer_booking.customer_booking_id','saloon_slots.slot_from','saloon_slots.slot_to','saloons.saloon_name' )
                               ->where('customer_booking.customer_booking_id', $customer_booking_id)->get();
     return view('vendor_orders.oder-details',compact('data'));
    }


}
