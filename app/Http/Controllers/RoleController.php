<?php

namespace App\Http\Controllers;

use App\RoleModel;
use Illuminate\Http\Request;
use DB;
use Session;
use Route;
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission');
    }
    public function index()
    {
        $role_list=RoleModel::all();
        return view('role.index',compact('role_list'));
    }

    
    public function create()
    {
       return view('role.add_role');
    }

    public function store(Request $request)
    {
       RoleModel::insert(['role_name'=>$request->role_name]);
       Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('RoleController@index');
    }

    public function edit($role_id)
    {
        $role_list=RoleModel::where('role_id',$role_id)->get();
        return view('role.edit_role',compact('role_list'));
    }

    public function update(Request $request,$role_id)
    {
        DB::table('roles')->where('role_id',$role_id)->update([
                           'role_name'=>$request->role_name ]);
        return redirect()->action('RoleController@index');
    }

    public function destroy($role_id)
    {
         DB::table('roles')->where('role_id', $role_id)->delete();
         Session::flash('message', 'Your Data Deleted Successfully');
         return redirect()->action('RoleController@index');
    }
}
