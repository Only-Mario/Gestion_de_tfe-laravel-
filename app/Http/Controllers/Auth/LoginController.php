<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        
    }

    public function showAdminLoginForm()
    {
        return view('admin.login', ['url' => 'admin']);
    }

    public function login(Request $request)
    {

         $input=$request->all();
          $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required',
          ]);
          if(Auth::attempt(array('email'=>$input['email'],'password'=>$input['password'])))
          {
            if(Auth::user()->is_admin==1){
                return redirect()->route('dashboard');
            }
            else{
                 return redirect()->route('welcome');
            }
          }
          else{
            return redirect()->route('login')->with('error','Email or Password is not correct');
          }
    }

}
