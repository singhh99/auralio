<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class ErrorController extends Controller
{
    public function access_denied()
    {
    	return view('error_page.access_denied');
    }
    public function access()
    {
    	$data = DB::table('users')
          ->join('roles', 'roles.role_id', '=', 'users.role_id')
          ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.role_id')
          ->join('permissions', 'permissions.permission_id', '=', 'role_has_permissions.permission_id')
          ->where('users.id', Auth::user()->id)
          ->get()->toArray();
        if(array_search('dashboard', $data) == false)
        {
        	return redirect($data[0]->permission_url);
        }
    	return redirect('home');
}
}
