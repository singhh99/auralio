<?php

namespace App\Http\Controllers;

use App\SaloonModel;
use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use DB;
use File;
use Illuminate\Support\Facades\Log;
use Session;
use Route;
use Validator;

class RevenueController extends Controller
{
    public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();
    }
    public function index(){

        $this->middleware('permission');
        
        $saloon_list=SaloonModel::where('saloon_status',0)->get();
        foreach($saloon_list as $key){
            $key->cash_revenue=$this->totalrevenuebycash($key->saloon_id);
            $key->online_revenue=$this->totalrevenue_viaonline($key->saloon_id);
            $key->whole_revenue=$this->totalrevenue($key->saloon_id);
        }
        // $totalrevenue=$this->totalrevenue($saloon_list[0]->saloon_id);
        
         return view('revenue.index',compact('saloon_list'));

    }
    public function totalrevenue($id){
        $booking_data=DB::table('customer_booking')->where('saloon_id',$id)->where('booking_status_id',10)->whereIn('payment_type',['cash','online'])
        ->get();
        $totalrevenue=0;
        foreach($booking_data as $key){
            $totalrevenue=$totalrevenue+$key->total_price;
        }
        return $totalrevenue;
      }

    public function totalrevenuebycash($id){
      $booking_data=DB::table('customer_booking')->where('saloon_id',$id)->where('booking_status_id',10)->where('payment_type','cash')
      ->get();
      $totalrevenue=0;
      foreach($booking_data as $key){
          $totalrevenue=$totalrevenue+$key->total_price;
      }
      return $totalrevenue;
    }
    
    public function totalrevenue_viaonline($id){
        $booking_data=DB::table('customer_booking')->where('saloon_id',$id)->where('booking_status_id',10)->where('payment_type','online')
        ->get();
        $totalrevenue=0;
        foreach($booking_data as $key){
            $totalrevenue=$totalrevenue+$key->total_price;
        }
        return $totalrevenue;
      }


    public function earningbycash(Request $request,$saloon_id){

        $bookings_detail = DB::table('customer_booking')
        ->join('saloons','saloons.saloon_id','=','customer_booking.saloon_id')
        ->leftJoin('booking_revenues','customer_booking.customer_booking_id','=','booking_revenues.customer_booking_id')
        ->where('customer_booking.saloon_id',$saloon_id)->where('customer_booking.booking_status_id',10)->where('customer_booking.payment_type','cash')
        ->select('saloons.saloon_name','customer_booking.customer_booking_code','customer_booking.customer_booking_date','customer_booking.payment_type','customer_booking.total_price','booking_revenues.commission_rate','booking_revenues.amount_build','booking_revenues.settlement_amount')
        ->get();
        $totalrevenue=0;
        foreach($bookings_detail as $key){
            $totalrevenue=$totalrevenue+$key->total_price;
        }
        // $totalrevenue;

        $saloon_name=$bookings_detail[0]->saloon_name;
        return view('revenue.earningbycash',compact('bookings_detail','totalrevenue','saloon_name'));

    }
    public function earningviaonline(Request $request,$saloon_id){

        $bookings_detail = DB::table('customer_booking')
        ->join('saloons','saloons.saloon_id','=','customer_booking.saloon_id')
        ->leftJoin('booking_revenues','customer_booking.customer_booking_id','=','booking_revenues.customer_booking_id')
        ->where('customer_booking.saloon_id',$saloon_id)->where('customer_booking.booking_status_id',10)->where('customer_booking.payment_type','online')
        ->select('saloons.saloon_name','customer_booking.customer_booking_code','customer_booking.customer_booking_date','customer_booking.payment_type','customer_booking.total_price','booking_revenues.commission_rate','booking_revenues.amount_build','booking_revenues.settlement_amount')
        ->get();
        $totalrevenue=0;
        foreach($bookings_detail as $key){
            $totalrevenue=$totalrevenue+$key->total_price;
        }
        $saloon_name=$bookings_detail[0]->saloon_name;
        return view('revenue.earningviaonline',compact('bookings_detail','totalrevenue','saloon_name'));

    }
    public function earningintotal(Request $request,$saloon_id){

        $bookings_detail = DB::table('customer_booking')
        ->join('saloons','saloons.saloon_id','=','customer_booking.saloon_id')
        ->leftJoin('booking_revenues','customer_booking.customer_booking_id','=','booking_revenues.customer_booking_id')
        ->where('customer_booking.saloon_id',$saloon_id)->where('customer_booking.booking_status_id',10)->whereIn('customer_booking.payment_type',['online','cash'])
        ->select('customer_booking.customer_booking_code','customer_booking.customer_booking_date','customer_booking.payment_type','customer_booking.total_price','booking_revenues.commission_rate','booking_revenues.amount_build','saloons.saloon_name','booking_revenues.settlement_amount')
        ->get();
        $totalrevenue=0;
        foreach($bookings_detail as $key){
            $totalrevenue=$totalrevenue+$key->total_price;
        }
        $saloon_name=$bookings_detail[0]->saloon_name;
        return view('revenue.earningintotal',compact('bookings_detail','totalrevenue','saloon_name'));

    }

    public function get_vendor_earnings(Request $request){
   

    if(empty($request))
    {
        return $this->CommonController->errorResponse('Please Provide correct data', 200);
    }else{
            $earnings = DB::table('customer_booking')
            ->join('saloons','saloons.saloon_id','=','customer_booking.saloon_id')
            ->leftJoin('booking_revenues','customer_booking.customer_booking_id','=','booking_revenues.customer_booking_id')
            ->where('customer_booking.saloon_id',$request->saloon_id)->where('customer_booking.booking_status_id',10)->whereIn('customer_booking.payment_type',['online','cash'])
            ->select('customer_booking.customer_booking_code','customer_booking.customer_booking_date','customer_booking.payment_type','customer_booking.total_price','booking_revenues.commission_rate','booking_revenues.amount_build','saloons.saloon_name','booking_revenues.settlement_amount')
            ->get();
            // dd($earnings);
            return $this->CommonController->successResponse($earnings,' Data Fetched succesfuly',200);

      }

    }
}
