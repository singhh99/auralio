<?php

namespace App\Http\Controllers;

use App\FeatureModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB;
use Session;

class FeatureController extends Controller
{
    
    public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();
    }
    public function index()
    {
        
        $feature_list=FeatureModel::all();
        return view('feature.index',compact('feature_list'));
    }

   
    public function create()
    {
        return view('feature.add_feature');
    }

    
    public function store(Request $request)
    {   
        FeatureModel::insert(['feature_name'=>$request->feature_name]);
        Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('FeatureController@index');
 
    }

   
    public function show(FeatureModel $featureModel)
    {
        //
    }

    public function edit($feature_id)
    { 
        $feature_list=FeatureModel::where('feature_id',$feature_id)->get();
   //get()->toArray(); dd($feature_list[0]['feature_id']);
        return view('feature.edit_feature',compact('feature_list'));
    }

    
    public function update(Request $request,$feature_id)
    {
        DB::table('features')->where('feature_id',$feature_id)->update(['feature_name'=>$request->feature_name ]);
        return redirect()->action('FeatureController@index');
    }

    public function destroy($feature_id)
    {
       DB::table('features')->where('feature_id', $feature_id)->delete();
       Session::flash('message', 'Your Data Deleted Successfully');
      return redirect()->action('FeatureController@index');
    }

    // function fetch all features api 
    public function all_features( Request $request)
    {
        $feature_list=FeatureModel::all();
        return $this->CommonController->successResponse($feature_list,' Data Saved succesfuly',200);
    }
}
