<?php

namespace App\Http\Controllers;

use App\PermissionModel;
use Illuminate\Http\Request;
use DB;
use Session;
use Route;
class PermissionController extends Controller
{

    public function index()
    {
       $permission_list=PermissionModel::all();
        return view('permission.index',compact('permission_list'));
    }

    public function create()
    {
       return view('permission.add_permission');
    }

    public function store(Request $request)
    {
       PermissionModel::insert(['permission_name'=>$request->permission_name,
                                'permission_url'=>$request->permission_url]
                                 );
        Session::flash('message', 'Your Data save Successfully');
        return redirect()->action('PermissionController@index');
    }

    public function edit($permission_id)
    {
        $permission_list=PermissionModel::where('permission_id',$permission_id)->get();

        return view('permission.edit_permission',compact('permission_list'));
    }

    public function update(Request $request,$permission_id)
    {
       DB::table('permissions')->where('permission_id',$permission_id)->update(['permission_name'=>$request->permission_name,'permission_url'=>$request->permission_url ]);
        return redirect()->action('PermissionController@index');
    }

    
    public function destroy($permission_id)
    {
        DB::table('permissions')->where('permission_id', $permission_id)->delete();
         Session::flash('message', 'Your Data Deleted Successfully');
         return redirect()->action('PermissionController@index');
    }
}
