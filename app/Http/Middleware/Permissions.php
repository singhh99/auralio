<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Auth;
use Route;

class Permissions
{
    public function handle($request, Closure $next)
    {
    
        $data = DB::table('users')
          ->join('roles', 'roles.role_id', '=', 'users.role_id')
          ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.role_id')
          ->join('permissions', 'permissions.permission_id','=','role_has_permissions.permission_id')
          ->where('users.id', Auth::user()->id)
          ->get();
           // dd($data);
        $path = $request->route()->uri;

        foreach($data as $key)
        {
            if($path == $key->permission_url)
            {
                 return $next($request);
            }
            
        }

        return redirect('permisiondenied');
    }
}
