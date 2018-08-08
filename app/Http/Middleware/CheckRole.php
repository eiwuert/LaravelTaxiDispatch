<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use Session;
use Auth;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role)
    {
        
        $value = $request->session()->get('user_role');
        if(!$value){
            Session::flash('message', "Please Login again to access application");
            return redirect()->intended('/');
        }
        //check user exists or not
        if(!auth()->check()){
             //return Response("Not having a permission",401);
			 	Session::flash('message', "Please Login to access application");
				return redirect()->intended('/');
        }
            
		//check having single role or multiple    
        if (strpos($role, '|') !== false) {
            $role=explode('|',$role);
        }
        
        $role=isset($role) ? $role:null;
        if($request->user()->hasAnyRole($role) || !$role){
            return $next($request);
        }
			return Response("Dont having a permission to access this page",401);
    }
}
