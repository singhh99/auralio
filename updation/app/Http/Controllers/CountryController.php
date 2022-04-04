<?php

namespace App\Http\Controllers;

use App\CountryModel;
use Illuminate\Http\Request;
use DB;

class CountryController extends Controller
{
   
    public function index()
    {
        $country_list=CountryModel::all();
        return view('country.index',compact('country_list'));
    }

    public function create()
    {
        
    }
    
    public function store(Request $request)
    {
        
        CountryModel::insert(['country_name'=>$request->country_name]);
        return redirect()->action('CountryController@index');
    }

    public function show(CountryModel $countryModel)
    {
       
    }
    public function edit($country_id)
    {
        $data=CountryModel::where('country_id',$country_id)->get();
        return view('country.edit_country',compact('data'));
    }
    public function update(Request $request,  $country_id)
    {
        DB::table('countries')->where('country_id',$country_id)->update(['country_name'=>$request->country_name]);
        return redirect()->action('CountryController@index');
    }
    public function destroy($country_id)
    {
         DB::table('countries')->where('country_id', $country_id)->delete();
        return redirect()->action('CountryController@index');
    }
}
