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

   
    public function edit($aggrement_id)
    {
       
        $aggrement_list=DB::table('salon_aggrement')->where('aggrement_id',$aggrement_id)->get();

        return view('aggrement.edit_aggrement',compact('aggrement_list'));
    }
    
   public function update(Request $request, $aggrement_id)
    {
         $aggrement_file = $request->file('aggrement_file');
        if($aggrement_file == '')   
         {
            
            DB::table('salon_aggrement')->where('aggrement_id',$aggrement_id)
                                        ->update(['aggrement_type'=>$request->aggrement_type ]);
         }
         else
         {
            $data = AggrementModel::where('aggrement_id', $aggrement_id)->get();
            $image_path = public_path('/images/aggrement/'.$data[0]->aggrement_file);
            if(File::exists($image_path))
            {
                File::delete($image_path);
            }
             $aggrement_file = $this->CommonController->upload_image($request->file('aggrement_file'),"aggrement_file");
              DB::table('salon_aggrement')->where('aggrement_id',$aggrement_id)
                                      ->update(['aggrement_type'=>$request->aggrement_type ,
                                                'aggrement_file'=>$aggrement_file]);
         }
        return redirect()->action('AggrementController@index');
    }
   
    public function destroy(AggrementModel $aggrementModel)
    {
        //
    }
}
