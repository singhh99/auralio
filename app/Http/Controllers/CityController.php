<?php

namespace App\Http\Controllers;

use App\CityModel;
use Illuminate\Http\Request;
use DB;

class CityController extends Controller
{

    public function index()
    {
       $city_list = DB::table('cities')
            ->join('states', 'cities.state_id', '=', 'states.state_id')
            ->select('cities.*', 'states.state_name')
            ->get();
        return view('city.index',compact('city_list'));
    }

    public function create()
    {
        $data= DB::table('states')->get();
        return view('city.add_city',compact('data'));
    }

   
    public function store(Request $request)
    {
        CityModel::insert(['state_id'=>$request->state_id,'city_name'=>$request->city_name]);
        return redirect()->action('CityController@index');
    }

    public function show(CityModel $cityModel)
    {
        //
    }

    public function edit( $city_id)
    {
       $city_list['state_list']= DB::table('states')->get();
       $city_list['city_data']=CityModel::where('city_id',$city_id)->get();
        return view('city.edit_city',$city_list);
    }

    public function update(Request $request, $city_id)
    {
        DB::table('cities')->where('city_id',$city_id)->update(['state_id'=>$request->state_id,
                                                                'city_name'=>$request->city_name ]);
        return redirect()->action('CityController@index');
    }

    public function destroy($city_id)
    {
        // echo $city_id;
        DB::table('cities')->where('city_id', $city_id)->delete();
        return redirect()->action('CityController@index');
    }
}
