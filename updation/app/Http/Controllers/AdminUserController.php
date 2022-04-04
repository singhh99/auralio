<?php

namespace App\Http\Controllers;
use App\AdminUserModel;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Hash;


class AdminUserController extends Controller
{
   
    public function index()
    {
       $user_list=AdminUserModel::all();
        return view('admin_user.index',compact('user_list'));
    }
   
    public function create()
    {
       return view('admin_user.add_user');
    }

    public function store(Request $request)
    {
        AdminUserModel::insert(['name'=>$request->name,
                                'email'=>$request->email,
                                'mobile_no'=>$request->mobile_no,
                                'password'=> Hash::make($request->password),]);
        Session::flash('message','Your Data save Successfully');
        return redirect()->action('AdminUserController@index');
    }

    public function show(AdminUserModel $adminUserModel)
    {
    }

    
    public function edit($id)
    {
        $user_list=AdminUserModel::where('id',$id)->get();
        return view('admin_user.edit_user',compact('user_list'));
    }

    public function update(Request $request,$id)
    {
        DB::table('users')->where('id',$id)->update(['name'=>$request->name,
                                'email'=>$request->email,
                                'mobile_no'=>$request->mobile_no,
                                'password'=>$request->password,]);
        return redirect()->action('AdminUserController@index');
    }

    public function destroy($id)
    {
       DB::table('users')->where('id', $id)->delete();
      Session::flash('message', 'Your Data Deleted Successfully');
      return redirect()->action('AdminUserController@index');
    }
}
