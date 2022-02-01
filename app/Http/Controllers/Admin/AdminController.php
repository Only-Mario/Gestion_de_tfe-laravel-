<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
class AdminController extends Controller
{
   
    public function index(){
        flash()->overlay('You are now a Laracasts member!', 'Yay');

        return view('admin.dashboard');
    }
}
