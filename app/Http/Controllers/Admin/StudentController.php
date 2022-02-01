<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function new(Request $request)
    {
        $request->password=Hash::make($request->password);
        User::create($request->all());
        return response('Etudiant ajouté avec success');
    }

    public function update($id)
    {
        
    }

    public function delete($id)
    {
        $user=User::find($id);
        $user->delete();
        return redirect()->back()->with("success","User delete succesfully");
    }
}
