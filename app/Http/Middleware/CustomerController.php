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
   
    public function index()
    {
        //
    }
    public function create()
    {
        //
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
          return $this->CommonController->successResponse($result,'Customer Already Exist',200);
         }
        else
        { 
         $customer_mobile=$request->customer_mobile;
         $otp = mt_rand(1000,9999);
         $msg = "Dear customer,$otp is your OTP , please enter the otp to proceed.Thank You. ";
        Curl::to('http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'.$customer_mobile.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2')->get();
         $customer_id=CustomerModel::insertGetId(['customer_mobile'=>$customer_mobile]);
         DB::table('customers')->where('customer_mobile',$customer_mobile)->update(['otp'=>$otp]);
         return $this->CommonController->successResponse($otp,'OTP send succesfuly',200);
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
              if($result1[0]->otp==$request->otp && $result1[0]->customer_mobile==$request->customer_mobile )
              {
                  DB::table('customers')->where('customer_id',$customer_id)
                                       ->update([
                                         'customer_name'=>$request->customer_name,
                                         'customer_gender'=>$request->customer_gender,
                                         'refreal_code'=>$bytes,
                                         'refreal_by'=>$request->refreal_by,
                                         'customer_latitude'=>$request->customer_latitude,
                                         'customer_longitude'=>$request->customer_longitude]);
                  $customer_code = ($customer_id<10) ? 'C00'.$customer_id : ($customer_id >=10 && $customer_id < 100) ? 'C0'.$customer_id : 'C'.$customer_id;
                  DB::table('customers')->where('customer_id',$customer_id)->update(['customer_code'=>$customer_code]);
                  $customers=DB::table('customers')->where('customer_id',$customer_id)->get();
                  return $this->CommonController->successResponse($customers,'Login succesfuly',200);
              }
              else
                {
                    return $this->CommonController->errorResponse(NULL,'wrong otp',201);
                }
            }
            else
            {
              return $this->CommonController->errorResponse('customer does not exist',200);
            }
        } 
      }
    
    public function show($customer_id)
    {
        //
    }

    public function edit($customer_id)
    {
       
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

    public function destroy($id)
    {
        //
    }

   public function salon_filter(Request $request)
   {

     $GLOBALS['saloon_type_id']=$request->saloon_type_id;
     $GLOBALS['saloon_name']=$request->saloon_name;
      $data = DB::table('saloons')->where('saloon_status',0)
                                  ->where('admin_approval',1)
                                  ->where(function($query) {
                                    $query->orWhere('saloon_name' ,'like', '%' .$GLOBALS['saloon_name'] . '%');
                                    for($i = 0; $i < count($GLOBALS['saloon_type_id']); $i++)
                                    {
                                     $query->orWhere('saloon_type_id', '=', $GLOBALS['saloon_type_id'][$i]);
                                     }
                                   })->get();
      if(count($data)==0)
      {
         return $this->CommonController->errorResponse(null,' no saloon avilable with this filter',201);
      }
      else
      {
         return $this->CommonController->successResponse($data,' Data fetched succesfuly',200);
       }                           
    }

    public function salon_deatils($saloon_id )
    {
        $salon_details= DB::table('saloons')
        ->where('saloons.saloon_id', $saloon_id)
        ->get(); 
        if(count($salon_details) > 0)
        {
          $salon_details[0]->Services = DB::table('saloons_services')->where('saloon_id', $saloon_id)->get();
          $salon_details[0]->Images = DB::table('saloons_images')->where('saloon_id', $saloon_id)->get();
          $salon_details[0]->Slots = DB::table('saloon_slots')->where('saloon_id', $saloon_id)->get(); 
        }
        return $this->CommonController->successResponse($salon_details,' Data fetched succesfuly',200);

    }

    public function customer_rating(Request $request)
    {

       $data=DB::table('customer_booking')->where('customer_booking_id', $customer_booking_id)
                                          ->where('payment_status_id',9)->get();
      if($data==1)
      {
        CustomerModel::insertGetId(['customer_id'=>$request->customer_id,'customer_booking_id'=>$customer_booking_id,'saloon_id'=>$request->saloon_id,'customer_rating'=>$request->customer_rating]);
          return $this->CommonController->successResponse(null,'rating saved successfully',200);
      }
     else {
          return $this->CommonController->errorResponse(null,'rating not saved',201);
     }
    }
}
