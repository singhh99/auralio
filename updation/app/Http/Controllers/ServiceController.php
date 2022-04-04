<?php

namespace App\Http\Controllers;

use App\ServiceModel;
use Illuminate\Http\Request;
use DB;
use route;
class ServiceController extends Controller
{

    public function index()
    {
        $service_list=ServiceModel::all();
        return view('service.index',compact('service_list'));
    }

    public function create()
    {
        return view('service.add_service');
    }

    public function store(Request $request)
    {
        ServiceModel::insert(['service_name'=>$request->service_name,
                              'service_price'=>$request->service_price,
                              'saloon_id'=>$request->saloon_id ]
                                    );
        Session::flash('message', 'Your Data save Successfully');
        redirect()->action('SaloonController@index');
    }


    public function show(ServiceModel $serviceModel)
    {
        //
    }

    public function edit($service_id)
    {
        $service_list=ServiceModel::where('service_id',$service_id)->get();
        return view('service.edit_service',compact('service_list'));
    }

    public function update(Request $request,$service_id)
    {
        DB::table('services')->where('service_id',$service_id)->update(['service_name'=>$request->service_name ]);
        return redirect()->action('ServiceController@index');
    }

   
    public function destroy($service_id)
    {
       DB::table('services')->where('service_id', $service_id)->delete();
       Session::flash('message', 'Your Data Deleted Successfully');
      return redirect()->action('ServiceController@index');
    }
}
