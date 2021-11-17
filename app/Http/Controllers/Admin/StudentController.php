<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function new($request)
    {
        Log::info($request);
        // $request->password=Hash::make($request->password);
        // User::create($request->all());
        return response()->json(['success'=>'Etudiant ajouté avec success']);
    }

    public function update($id)
    {
        
    }

    public function delete($id)
    {
        $student=User::find($id);
        $student->delete();
        return redirect()->back()->with("success","Student delete succesfully");
    }
}
