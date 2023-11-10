<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {           
        try {

            if(auth()->user()!=null)
            {   
                if(auth()->user()->is_admin==1){
                    return $next($request);
                }    
            } 
            return redirect('welcome')->with('error',"You don't have admin access");
        } catch (Exception $e) {
            return redirect('welcome')->with('error',"You don't have admin access");
        }
        
    }
}
