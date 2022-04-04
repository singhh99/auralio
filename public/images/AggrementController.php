<?php

namespace App\Http\Controllers;

use App\AggrementModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB;
use File;
use Session;
class AggrementController extends Controller
{

     public function __construct(Request $request)
    {
          $this->middleware('permission', ['only' => ['create', 'edit','store','index','update','destroy']]);
         $this->CommonController=new CommonController();
    }
    
    public function index()
    {
       $this->middleware('permission');
        $aggrement_list=AggrementModel::all();
        // dd($reason_list);
        return view('aggrement.index',compact('aggrement_list')); 
    }

   
    public function create()
    {
        $this->middleware('permission');
        return view('aggrement.add_aggrement');
    }

   
    public function store(Request $request)
    {
        $this->middleware('permission');
         if($request->file('aggrement_file'))
            {
                $aggrement_file = $this->CommonController->upload_image($request->file('aggrement_file'),"aggrement_file");
            }
            else
            {
                $aggrement_file = '';
            }
        AggrementModel::insert(['aggrement_type'=>$request->aggrement_type,'aggrement_file'=>$aggrement_file]);
        Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('AggrementController@index');
 
    }

    
    public function show(AggrementModel $aggrementModel)
    {
        //
    }

   
    public function edit(AggrementModel $aggrementModel)
    {
        //
    }

    
    public function update(Request $request, AggrementModel $aggrementModel)
    {
        //
    }

   
    public function destroy(AggrementModel $aggrementModel)
    {
        //
    }
}
