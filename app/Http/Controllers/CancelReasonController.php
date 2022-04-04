<?php

namespace App\Http\Controllers;

use App\CancelReasonModel;
use Illuminate\Http\Request;
use DB;
use Session;

class CancelReasonController extends Controller
{

     public function __construct(Request $request)
    {
      $this->middleware('permission', ['only' => ['create', 'edit','store','index','update','destroy']]);
     $this->CommonController=new CommonController();
    }
    public function index()
    {
        $this->middleware('permission');
        $reason_list=CancelReasonModel::all();
        // dd($reason_list);
        return view('cancel_reason.index',compact('reason_list'));
    }


    public function create()
    {
        $this->middleware('permission');
        return view('cancel_reason.add_reason');
    }


    public function store(Request $request)
    {

        $this->middleware('permission');
        CancelReasonModel::insert(['reason_name'=>$request->reason_name,
                                   'reason_for'=>$request->reason_for]);
        Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('CancelReasonController@index');
    }


    public function show(CancelReasonModel $cancelReasonModel)
    {
        //
    }


    public function edit($reason_id)
    {
      $this->middleware('permission');
      $reason_list=CancelReasonModel::where('reason_id',$reason_id)->get();
      //get()->toArray(); dd($feature_list[0]['feature_id']);
        return view('cancel_reason.edit_reason',compact('reason_list'));
    }

    public function update(Request $request,$reason_id)
    {

        $this->middleware('permission');
        DB::table('cancellation_reasons')->where('reason_id',$reason_id)->update(['reason_name'=>$request->reason_name,'reason_for'=>$request->reason_for ]);
        return redirect()->action('CancelReasonController@index');
    }


    public function destroy($reason_id)
    {
       $this->middleware('permission');
       DB::table('cancellation_reasons')->where('reason_id', $reason_id)->delete();
       Session::flash('message', 'Your Data Deleted Successfully');
      return redirect()->action('CancelReasonController@index');
    }

    public  function customer_cancel_reason(Request $request)
    {
      $reason_list=CancelReasonModel::where('reason_for','Customer')->get();
      return $this->CommonController->successResponse($reason_list,'Customer reason fetched Successfully',200);
    }

     public  function salon_cancel_reason(Request $request)
    {

      $reason_list=CancelReasonModel::where('reason_for','Salon')->get();
      return $this->CommonController->successResponse($reason_list,'Saloon reason fetched Successfully',200);
    }
}
