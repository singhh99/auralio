<?php

namespace App\Http\Controllers;

use App\RoleHasPermissionModel;
use App\RoleModel;
use Illuminate\Http\Request;
use Session;
use DB;

class RoleHasPermissionController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
         $this->middleware('permission');
    }
    public function index()
    {

        $roles = DB::table('roles')->orderBy('role_id','desc')->get();
        $permissions = DB::table('roles')
                ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.role_id')
                ->join('permissions', 'permissions.permission_id', '=', 'role_has_permissions.permission_id')
                ->get(); 
        for($i = 0; $i < count($roles); $i++)
        {
            $abc = [];
            for($j = 0; $j < count($permissions); $j++)
            {
                if($roles[$i]->role_id == $permissions[$j]->role_id)
                {
                    array_push($abc, $permissions[$j]);
                }
            }
            $roles[$i]->permissions = $abc;
        }
 
        // $roles_list=RoleHasPermissionModel::all();
        return view('role_permission.index',compact('roles'));
    }

    public function create()
    {
        $role_list=DB::table('roles')->get();
        $permission_list=DB::table('permissions')->get();
        return view('role_permission.add_role_permission',compact('role_list','permission_list'));
    }

    public function store(Request $request)
    {
       
        $role_id=RoleModel::insertGetId(['role_name'=>$request->role_name]);
       
        for($j = 0; $j < count($request->permission_id); $j++)
            {
              $data[] = ['role_id' =>$role_id, 'permission_id' => $request->permission_id[$j]];
            }
        DB::table('role_has_permissions')->insert($data);
        Session::flash('message', 'Your Data save Successfully');
       return redirect()->action('RoleHasPermissionController@index');
    }


    public function edit($role_id)
    {   

        $role_list=DB::table('roles')->where('role_id',$role_id)->get();
        $permission_list=DB::table('permissions')->get();
        $role_details=DB::table('role_has_permissions')->where('role_id',$role_id)->get();
        //dd($role_details);

        return view('role_permission.edit_role_permission',compact('permission_list','role_details','role_list','role_id'));

    }

    public function update(Request $request, $role_id)
    {
        
         DB::table('role_has_permissions')->where('role_id', $role_id)->delete();
         for($j = 0; $j < count($request->permission_id); $j++)
            {
              $data[] = ['role_id' =>$role_id, 'permission_id' => $request->permission_id[$j]];
            }
        DB::table('role_has_permissions')->insert($data);
        Session::flash('message', 'Your Data updated Successfully');
       return redirect()->action('RoleHasPermissionController@index');   
    }

    public function destroy($role_id)
    {
           
         DB::table('role_has_permissions')->where('role_id', $role_id)->delete();
         Session::flash('message', 'Your Data Deleted Successfully');
         return redirect()->action('RoleHasPermissionController@index');  
    }
}
