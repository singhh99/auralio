<?php

namespace App\Http\Controllers;

use App\SaloonTypeModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB;
use Session;
use Route;
class SaloonTypeController extends Controller
{

     public function __construct(Request $request)
    {
      $this->middleware('permission', ['only' => ['create', 'edit','store','index','update','destroy']]);
     $this->CommonController=new CommonController();
    }

    public function index()
    {
      $this->middleware('permission');
       $saloon_list=SaloonTypeModel::all();
      return view('saloon_type.index',compact('saloon_list'));
    }


    public function create()
    {
        $this->middleware('permission');
        return view('saloon_type.add_saloon_type');

    }


    public function store(Request $request)
    {
      $this->middleware('permission');
       SaloonTypeModel::insert(['saloon_type_name'=>$request->saloon_type_name]);
       Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('SaloonTypeController@index');
    }


    public function show(SaloonTypeModel $saloonTypeModel)
    {
        //
    }


    public function edit($saloon_type_id)
    {
       $this->middleware('permission');
       $saloon_list=SaloonTypeModel::where('saloon_type_id',$saloon_type_id)->get();
        return view('saloon_type.edit_saloon_type',compact('saloon_list'));
    }


    public function update(Request $request,$saloon_type_id)
    {
       $this->middleware('permission');
       DB::table('saloon_types')->where('saloon_type_id',$saloon_type_id)->update(['saloon_type_name'=>$request->saloon_type_name ]);
        return redirect()->action('SaloonTypeController@index');
    }


    public function destroy($saloon_type_id)
    {
        // dd($saloon_type_id);
        $this->middleware('permission');
        DB::table('saloon_types')->where('saloon_type_id', $saloon_type_id)->delete();
         Session::flash('message', 'Your Data Deleted Successfully');

        return redirect()->action('SaloonTypeController@index');
    }
    //function for featch all saloon_type
   public function all_saloon_type(Request  $request)
   {
     $type_list=SaloonTypeModel::all();
      return $this->CommonController->successResponse($type_list,' Data Fetched succesfuly',200);
   }
}
