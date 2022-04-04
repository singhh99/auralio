<?php

namespace App\Http\Controllers;

use App\ServiceModel;
use Illuminate\Http\Request;
use DB;
use  App\Http\Controllers\CommonController;
use  Session;
class ServiceController extends Controller
{


    public function __construct(Request $request)
    {
     $this->middleware('permission', ['only' => ['create', 'edit','store','index','update','destroy']]);
     $this->CommonController=new CommonController();
    }
    public function index()
    {
        $this->middleware('permission');
        $service_list=ServiceModel::all();
        return view('service.index',compact('service_list'));
    }

    public function create()
    {
        $this->middleware('permission');
        return view('service.add_service');
    }

    public function store(Request $request)
    {
        $this->middleware('permission');
        ServiceModel::insert(['service_name'=>$request->service_name,]
                                    );
        Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('ServiceController@index');
    }


    public function show(ServiceModel $serviceModel)
    {
        //
    }

    public function edit($service_id)
    {
        $this->middleware('permission');
        $service_list=ServiceModel::where('service_id',$service_id)->get();
        return view('service.edit_service',compact('service_list'));
    }

    public function update(Request $request,$service_id)
    {
        $this->middleware('permission');
        DB::table('services')->where('service_id',$service_id)->update(['service_name'=>$request->service_name ]);
        return redirect()->action('ServiceController@index');
    }


    public function destroy($service_id)
    {
        $this->middleware('permission');
       DB::table('services')->where('service_id', $service_id)->delete();
       Session::flash('message', 'Your Data Deleted Successfully');
      return redirect()->action('ServiceController@index');
    }

    public function all_salloon_services(Request $request)
    {
        $service_list['services']=ServiceModel::all();
        $service_list['aggrement']=DB::table('salon_aggrement')->orderBy('aggrement_id','desc')->limit(1)->where('aggrement_type','Salon')->get();
        return $this->CommonController->successResponse($service_list,' Data fetched succesfuly',200);
    }
}
