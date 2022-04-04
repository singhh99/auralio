<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\CustomerModel;
use  App\Http\Controllers\CommonController;
use DB;
use File;
use Session;
use Curl;
class CustomerController extends Controller
{

   public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();

    }

    public function validateCustomer($request)
    {
        return Validator::make($request->all(),
         [
             'customer_mobile'=>['required','regex:/^([987]{1})(\d{1})(\d{8})$/','min:10','max:10','unique:customers']
            ]);
    }

    public function store(Request $request)
    {
         $validator = $this->validateCustomer($request);
        //   if($validator->fails())
        // {
        //     return $this->CommonController->errorResponse($validator->messages(), 422);
        // }

         $result=DB::table('customers')->where('customer_mobile',$request->customer_mobile)->get();
         if(count($result)>0)
         {
           $otp = mt_rand(1000,9999);
           $msg = "Dear customer,$otp is your OTP , please enter the otp to proceed.Thank You. ";
          Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'.$request->customer_mobile.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2')->get();
          DB::table('customers')->where('customer_mobile',$request->customer_mobile)->update(['otp'=>$otp]);
          return response()->json([ 'status'=> 'Success','message' => 'Customer Already exist','data' => $result, 'OTP' => $otp], 200);
          //return $this->CommonController->successResponse($result,$otp,200);
         }
        else
        {
         $customer_mobile=$request->customer_mobile;
         $otp = mt_rand(1000,9999);
         $msg = "Dear customer,$otp is your OTP , please enter the otp to proceed.Thank You. ";
        Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'.$customer_mobile.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2')->get();
         $customer_id=CustomerModel::insertGetId(['customer_mobile'=>$customer_mobile]);
         DB::table('customers')->where('customer_mobile',$customer_mobile)->update(['otp'=>$otp]);
         return response()->json([ 'status'=> 'Success','message' => 'otp sent succesfuly','data' => null, 'OTP' => $otp], 200);
       }
    }

    public function generate_code($customer_mobile)
    {
           $string = '["1","2","3","4","5","6","7","8","9","0","Q","W","E","R","T","Y","U","I","O","P","A","S","D","F","G","H","J","K","L","Z","X","C","V","B","N","M"]';
          $characterArray = json_decode($string);
          $string=implode($characterArray);
          $numbersystem = count($characterArray);
          $number = $customer_mobile;
          $encodedString = '';
          while ($number) {
              $encodedString .= $string[$number % $numbersystem];
              $number = floor($number / $numbersystem);
          }
          return $encodedString;
    }
     public function validateCustomerDetails($request)
     {
        return Validator::make($request->all(), [
             'customer_name'=>['required','string','min:2','regex:/^[a-zA-Z ]*$/'],
             'customer_gender'=>['required','string'],
             'Tc_checked'=>['accepted']
            ]);
     }
    public function customer_otp_verification(Request $request)
    {
      $idToken=$request->idToken;
      $customer_mobile=$request->customer_mobile;
      
      $key = 'AIzaSyDYm2hOQM9v0axvz7Rh_aOwJiyO2jQQAvk';
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key='.$key);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"idToken\":\"$idToken\"}");
      
      $headers = array();
      $headers[] = 'Content-Type: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
      $response = curl_exec($ch);
      if (curl_errno($ch)) 
      {
        echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);
      
      $response=json_decode($response, true);
    
      $diff=$response['users'][0]['providerUserInfo'][0]['rawId']??''; 
      $country_code="+91";
      $diff= preg_replace("/^\+?{$country_code}/", '',$diff);
     
           $result1=DB::table('customers')->where('customer_mobile',$request->customer_mobile)->get();
           $customer_id=$result1[0]->customer_id;
           $bytes = $this->generate_code($result1[0]->customer_mobile);
           $validator = $this->validateCustomerDetails($request);
          if($validator->fails())
          {
              return $this->CommonController->errorResponse($validator->messages(), 422);
          }
        else
        {
           if(count($result1)>0)
            {
              if(($diff==$customer_mobile) && $result1[0]->customer_mobile==$request->customer_mobile )
              {
                 DB::table('customers')->where('customer_id',$customer_id)
                                       ->update([
                                         'customer_name'=>$request->customer_name,
                                         'customer_gender'=>$request->customer_gender,
                                         'customer_email'=>$request->customer_email,
                                         'refreal_code'=>$bytes,
                                         'refreal_by'=>$request->refreal_by,
                                         'customer_latitude'=>$request->customer_latitude,
                                         'customer_longitude'=>$request->customer_longitude,
                                         ]);
                      $device_id=$request->device_id;                   
                    if(!empty($device_id)){
                      DB::table('customers')->where('customer_id',$customer_id)->update(['customer_device_id'=>$request->device_id]);
                    }                     
                   $customer_code = ($customer_id<10) ? 'C00'.$customer_id : (($customer_id >=10 && $customer_id < 100) ? 'C0'.$customer_id : 'C'.$customer_id);
                  DB::table('customers')->where('customer_id',$customer_id)->update(['customer_code'=>$customer_code]);
                  $customers=DB::table('customers')->where('customer_id',$customer_id)->get();
                  return $this->CommonController->successResponse($customers,'Login succesfuly',200);
              }
               else
                  {
                    return $this->CommonController->errorResponse('wrong otp',201);
                   }
            }
            else
            {
              return $this->CommonController->errorResponse('customer does not exist',200);
            }
        }
    }


    public function update_customer_details(Request $request, $customer_id)
    {

      $customer_image = $request->file('customer_image');
      if($customer_image == '')
      {
          DB::table('customers')->where('customer_id',$customer_id)->update([
                          'customer_name'=>$request->customer_name,
                          'customer_email'=>$request->customer_email,
                          'customer_gender'=>$request->customer_gender,]);
          return $this->CommonController->successResponse(NULL,' Data updated succesfuly',200);
      }
      else
      {
        $data = CustomerModel::where('customer_id', $customer_id)->get();
        $image_path = public_path('/images/customer_image/'.$data[0]->customer_image);
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
         $customer_image = $this->CommonController->upload_image($request->file('customer_image'),"customer_image");
          DB::table('customers')->where('customer_id',$customer_id)
            ->update( ['customer_name'=>$request->customer_name,
            'customer_email'=>$request->customer_email,
            'customer_gender'=>$request->customer_gender,
            'customer_image'=>$customer_image]);
        return $this->CommonController->successResponse(NUll,' Data updated succesfuly',200);
      }
    }

    public function customer_token_updation(Request $request)
    {
        $result1=DB::table('customers')->where('customer_id',$request->customer_id)->get();
       if(count($result1)>0)
       {
         DB::table('customers')->where('customer_id',$request->customer_id)
             ->update( ['customer_device_id'=>$request->customer_device_id]);
        return $this->CommonController->successResponse(NUll,' Token updated succesfuly',200);
       }
       else{
        return $this->CommonController->successResponse(NUll,' customer doesnot exist',201);
      }
    }

   public function salon_filter(Request $request)
   {
     $GLOBALS['saloon_type_id']=json_decode($request->saloon_type_id);
     $GLOBALS['saloon_name']=$request->saloon_name;
     $data['saloons'] = DB::table('saloons')->where('saloon_status',0)
                                  ->where('admin_approval',1)
                                  ->where('on_status',1)
                                  ->where(function($query)
                                   {
                                      $query->orWhere('saloon_name' ,'like', '%' .$GLOBALS['saloon_name'] . '%');
                                      for($i = 0; $i < count($GLOBALS['saloon_type_id']); $i++)
                                      {
                                       $query->orWhere('saloon_type_id', '=', $GLOBALS['saloon_type_id'][$i]);
                                       }
                                   })->get();
      $data['saloon_images'] = DB::table('saloons')
                ->join('saloons_images', 'saloons_images.saloon_id', '=', 'saloons.saloon_id')
                ->select('saloons_images.saloon_image','saloons_images.saloon_id')
                ->where('saloon_status',0)
                ->where('admin_approval',1)
                ->where('on_status',1)
                ->where(function($query)
                 {
                        $query->orWhere('saloon_name' ,'like', '%' .$GLOBALS['saloon_name'] . '%');
                        for($i = 0; $i < count($GLOBALS['saloon_type_id']); $i++)
                          {
                            $query->orWhere('saloon_type_id', '=', $GLOBALS['saloon_type_id'][$i]);
                          }
                })->get();

              for($i = 0; $i < count($data['saloons']); $i++)
              {
                  $abc = [];
                  for($j = 0; $j < count($data['saloon_images']); $j++)
                  {
                      if($data['saloons'][$i]->saloon_id == $data['saloon_images'][$j]->saloon_id)
                      {
                          array_push($abc, $data['saloon_images'][$j]);
                      }
                  }
                  $data['saloons'][$i]->saloon_images = $abc;
              }

// dd($data['saloons'][0]->saloon_lattitude);

       $distance = [];
       for($k=0; $k< count($data['saloons']);$k++)
       {
        $saloon_lat=$data['saloons'][$k]->saloon_lattitude;
        $saloon_long=$data['saloons'][$k]->saloon_longitude;
        $customer_lat=$request->customer_lattitude;
        $customer_log=$request->customer_longitude;
          $theta = $customer_lat - $saloon_lat;
          $dist = sin(deg2rad($customer_lat)) * sin(deg2rad($saloon_lat)) +  cos(deg2rad($customer_lat)) * cos(deg2rad($saloon_lat)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper('K');
           $data['saloons'][$k]->distance = round($miles * 1.609344 * 100)/100;

       }

      if(count($data)==0)
      {
         return $this->CommonController->errorResponse(null,' no saloon avilable with this filter',201);
      }
      else
      {
         return $this->CommonController->successResponse($data['saloons'],' Data fetched succesfuly',200);
       }
    }

    public function salon_deatils(Request $request,$saloon_id)
    {


        $salon_details= DB::table('saloons')->where('saloons.saloon_id', $saloon_id)->get();

        $features=$salon_details[0]->saloon_feature_id;
        $days=$salon_details[0]->saloon_working_days;
        if(count($salon_details) > 0)
        {

          $salon_details[0]->features = DB::table('features')
                                        ->whereIn('feature_id',json_decode($features))
                                        ->select('features.feature_id','features.feature_name','features.feature_image')->get();
          $salon_details[0]->working_days=DB::table('days')
                                         ->whereIn('day_id',json_decode($days))
                                         ->select('days.day_id','days.day_name')->get();
          $salon_details[0]->Services = DB::table('saloons_services')
                                       ->leftJoin('services', 'services.service_id', '=', 'saloons_services.service_name')
                                       ->select('saloons_services.*','services.service_name')
                                       ->where('saloon_id', $saloon_id)->get();
          $salon_details[0]->Images = DB::table('saloons_images')->where('saloon_id', $saloon_id)->get();
          $salon_details[0]->Slots = DB::table('saloon_slots')->where('saloon_id', $saloon_id)->get();
          if(!empty($request->customer_id)) {
            $customer_id = $request->customer_id;
            $salon_details[0]->is_favourite = DB::table('customer_fav_saloons')->where('saloon_id', $saloon_id)->where('customer_id',$customer_id)->exists();
          } else {
            $salon_details[0]->is_favourite = false;
          }
         }
        else
        {
          return $this->CommonController->errorResponse(null,' no saloon avilable',201);
        }
        return $this->CommonController->successResponse($salon_details,' Data fetched succesfuly',200);
    }

    public function salon_slots($saloon_id, $date)
    {
        $saloon_slots = DB::table('saloon_slots')->where('saloon_id', $saloon_id)->where('status','new')->get();

        foreach ($saloon_slots as &$saloon_slot) {
            $saloon_slot->is_available = DB::table('customer_booking')
                ->where('saloon_slot_id', $saloon_slot->saloon_slot_id)
                ->where('saloon_id', $saloon_id)
                ->where('customer_booking_date', $date)
                ->count() < $saloon_slot->total_seats;
        }

        return $this->CommonController->successResponse($saloon_slots,' Data fetched succesfuly',200);

    }
    public function salon_bookings($saloon_id, $date)
    {
        $salon_details= DB::table('customer_booking')
            ->where('customer_booking.saloon_id', $saloon_id)
            ->where('customer_booking.customer_schedule_date', $date)
            ->get();
//        if(count($salon_details) > 0)
//        {
//            $salon_details[0]->Services = DB::table('saloons_services')
//                ->join('services', 'services.service_id', '=', 'saloons_services.service_name')
//                ->select('saloons_services.*','services.service_name')
//                ->where('saloon_id', $saloon_id)->get();
//            $salon_details[0]->Images = DB::table('saloons_images')->where('saloon_id', $saloon_id)->get();
//            $salon_details[0]->Slots = DB::table('saloon_slots')->where('saloon_id', $saloon_id)->get();
//        }
        return $this->CommonController->successResponse($salon_details,' Data fetched succesfuly',200);

    }


    public function customer_rating(Request $request)
    {
        $customer_booking_id = $request->customer_booking_id;
        $data=DB::table('customer_booking')->where('customer_booking_id', $customer_booking_id)->get();

        if(count($data)==1)
        {
           $data=['customer_id'=>$request->customer_id,
                                      'customer_booking_id'=>$customer_booking_id,
                                      'saloon_id'=>$request->saloon_id,
                                      'customer_rating'=>$request->customer_rating];
            $checkdata=DB::table('customer_rating')->where('customer_booking_id',$customer_booking_id)->get();

            if(count($checkdata)==0){                      
              DB::table('customer_rating')->insert($data);}
            else {return $this->CommonController->errorResponse('rating exists already',201);}
          
          $total_rating= DB::table('customer_rating')
                                       ->where('saloon_id',$request->saloon_id)
                                       ->groupBy('saloon_id')->get(DB::raw('sum(customer_rating) as total'));
          $count1= DB::table('customer_rating')
                                       ->where('saloon_id',$request->saloon_id)
                                       ->where('customer_rating','<>',0)->get();
           $total_number=count($count1);
          $average= number_format($total_rating[0]->total /  $total_number, 1);
          
           DB::table('saloons')->where('saloon_id',$request->saloon_id)->update(['saloon_rating'=>$average]);
           return $this->CommonController->successResponse(null,'rating saved successfully',200);
        }
       else
        {
            return $this->CommonController->errorResponse(null,'rating not saved',201);
       }
    }
}
