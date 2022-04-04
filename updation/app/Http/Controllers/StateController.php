<?php

namespace App\Http\Controllers;

use App\StateModel;
use Illuminate\Http\Request;
use DB;

class StateController extends Controller
{
   
    public function index()
    {
       $state_list = DB::table('states')
            ->join('countries', 'states.country_id', '=', 'countries.country_id')
            ->select('states.*', 'countries.country_name')
            ->get();
        return view('state.index',compact('state_list'));
    }
    public function create()
    {
        $data= DB::table('countries')->get();
        return view('state.add_state',compact('data'));
 
    }
    public function store(Request $request)
    {
        StateModel::insert(['state_name'=>$request->state_name,'country_id'=>$request->country_id]);
        return redirect()->action('StateController@index');
    }
    public function show(StateModel $stateModel)
    {
        //
    }

    public function edit($state_id)
    {  
       $state_list['country_list']= DB::table('countries')->get();
       $state_list['state_data']=stateModel::where('state_id',$state_id)->get();
        return view('state.edit_state',$state_list);
    }

    public function update(Request $request, $state_id)
    {
        DB::table('states')->where('state_id',$state_id)->update(['country_id'=>$request->country_id,'state_name'=>$request->state_name ]);
        return redirect()->action('StateController@index');
    }

    public function destroy($state_id)
    {
       DB::table('states')->where('state_id', $state_id)->delete();
        return redirect()->action('StateController@index');
    }
}
