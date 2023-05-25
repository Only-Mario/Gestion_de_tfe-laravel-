<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PasswordUpdate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class passwordUpdateController extends Controller
{

   public static function index(PasswordUpdate $request, $id)
   {
      if (password_verify($request->input('password'), Auth::user()->password) == 1) {
         $a = User::find($id);
         $user = User::find($id);
         $user->update(['password' => Hash::make($request->input('new_password'))]);
         return $a->password . '--->' . $user->password;
         return redirect(route('store'));
      }

      return redirect()->back();
   }
}