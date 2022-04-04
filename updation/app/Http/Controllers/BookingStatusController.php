<?php

namespace App\Http\Controllers;

use App\BookingStatusModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB;
use Session;

class BookingStatusController extends Controller
{
     public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();
    }
    
    public function index()
    {
       $status_list=BookingStatusModel::all();
        return view('booking_status.index',compact('status_list'));
    }
   
    public function create()
    {
        return view('booking_status.add_booking_status');
    }

    
    public function store(Request $request)
    {
       BookingStatusModel::insert(['booking_status_name'=>$request->booking_status_name]);
       Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('BookingStatusController@index');
    }

    
    public function show(BookingStatusModel $bookingStatusModel)
    {
        //
    }

    
    public function edit($booking_status_id)
    {
       $status_list= BookingStatusModel::where('booking_status_id',$booking_status_id)->get();
        return view('booking_status.edit_booking_status',compact('status_list'));
    }

   
    public function update(Request $request,$booking_status_id)
    {
       DB::table('booking_statuses')->where('booking_status_id',$booking_status_id)->update(['booking_status_name'=>$request->booking_status_name ]);
        return redirect()->action('BookingStatusController@index');
    }

    public function destroy($booking_status_id)
    {
        DB::table('booking_statuses')->where('booking_status_id', $booking_status_id)->delete();
       Session::flash('message', 'Your Data Deleted Successfully');
      return redirect()->action('BookingStatusController@index');
    }

    public function all_booking_status()
    {
         $status_list=BookingStatusModel::all();
        return $this->CommonController->successResponse($status_list,' Data fetched succesfuly',200);
    }
}
