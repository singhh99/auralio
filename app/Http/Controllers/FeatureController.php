<?php

namespace App\Http\Controllers;

use App\FeatureModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB;
use File;
use Session;

class FeatureController extends Controller
{
    public function __construct(Request $request)
    {
     $this->middleware('permission', ['only' => ['create', 'edit','store','index','update','destroy']]);
     $this->CommonController=new CommonController();
    }
    
    public function index()
    {
        $this->middleware('permission');
        $feature_list=FeatureModel::all();
        return view('feature.index',compact('feature_list'));
    }

    public function create()
    {
        $this->middleware('permission');
        return view('feature.add_feature');
    }

    
    public function store(Request $request)
    {   
        $this->middleware('permission');
         if($request->file('feature_image'))
            {
                $feature_image = $this->CommonController->upload_image($request->file('feature_image'),"feature_image");
            }
            else
            {
                $feature_image = '';
            }
        FeatureModel::insert(['feature_name'=>$request->feature_name,'feature_image'=>$feature_image]);
        Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('FeatureController@index');
 
    }

   
    public function show(FeatureModel $featureModel)
    {
        //
    }

    public function edit($feature_id)
    { 
        $this->middleware('permission');
        
        $feature_list=FeatureModel::where('feature_id',$feature_id)->get();
   //get()->toArray(); dd($feature_list[0]['feature_id']);
        return view('feature.edit_feature',compact('feature_list'));
    }

    
    public function update(Request $request,$feature_id)
    {
        $this->middleware('permission');
         $feature_image = $request->file('feature_image');
        if($feature_image == '')   
         {
                 DB::table('features')->where('feature_id',$feature_id)
                                      ->update(['feature_name'=>$request->feature_name ]);
         }
         else
         {
            $data = FeatureModel::where('feature_id', $feature_id)->get();
            $image_path = public_path('/images/feature/'.$data[0]->feature_image);
            if(File::exists($image_path))
            {
                File::delete($image_path);
            }
             $feature_image = $this->CommonController->upload_image($request->file('feature_image'),"feature_image");
              DB::table('features')->where('feature_id',$feature_id)
                                      ->update(['feature_name'=>$request->feature_name ,
                                                'feature_image'=>$feature_image]);
         }
        return redirect()->action('FeatureController@index');
    }

    public function destroy($feature_id)
    {
       $this->middleware('permission');
       DB::table('features')->where('feature_id', $feature_id)->delete();
       Session::flash('message', 'Your Data Deleted Successfully');
      return redirect()->action('FeatureController@index');
    }

    // function fetch all features api 
    public function all_features( Request $request)
    {
        $feature_list=FeatureModel::all();
        return $this->CommonController->successResponse($feature_list,' Data fetched succesfuly',200);
    }
}
