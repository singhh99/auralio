<?php

namespace App\Http\Controllers;
use App\AdminUserModel;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Hash;
use Route;


class AdminUserController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission');
    }
    public function index()
    {
        // $routeCollection = \Route::getRoutes();
        // dd($routeCollection);
        
         $user_list = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->select('users.*', 'roles.role_name')
            ->get();
       // $user_list=AdminUserModel::all();
        return view('admin_user.index',compact('user_list'));
    }
   
    public function create()
    {
        $role_list=DB::table('roles')->get();
        return view('admin_user.add_user',compact('role_list'));
    }

    public function store(Request $request)
    {
        AdminUserModel::insert(['name'=>$request->name,
                                'email'=>$request->email,
                                'mobile_no'=>$request->mobile_no,
                                'role_id'=>$request->role_id,
                                'password'=> Hash::make($request->password),]);
        Session::flash('message','Your Data save Successfully');
        return redirect()->action('AdminUserController@index');
    }

    public function show(AdminUserModel $adminUserModel)
    {
    }

    
    public function edit($id)
    {
        $role_list=DB::table('roles')->get();
        $user_list=AdminUserModel::where('id',$id)->get();
        return view('admin_user.edit_user',compact('user_list','role_list'));
    }

    public function update(Request $request,$id)
    {
        DB::table('users')->where('id',$id)->update(['name'=>$request->name,
                                'email'=>$request->email,
                                'mobile_no'=>$request->mobile_no,
                                'role_id'=>$request->role_id,
                                'password'=>$request->password,]);
        return redirect()->action('AdminUserController@index');
    }

    public function destroy($id)
    {
       DB::table('users')->where('id', $id)->delete();
       Session::flash('message', 'Your Data Deleted Successfully');
       return redirect()->action('AdminUserController@index');
    }

    public function update_user_status(Request $request)
    {
         $id = $request->id;
         $status_update= DB::table('users')->where('id',$id)->update(['status'=>$request->status]);
         if($status_update)
         {
            return response()->json("User Status updated");
         }
         else
         {
            return response()->json("User Status not updated");
         }

    }
}
