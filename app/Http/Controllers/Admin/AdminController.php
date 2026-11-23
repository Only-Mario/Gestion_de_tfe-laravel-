<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function store(StoreAdminRequest $request)
    {
        if (!Hash::check($request->input('passwordV'), Auth::user()->password)) {
            return redirect()->back()->withErrors(['passwordV' => 'Mot de passe incorrect.'])->withInput();
        }

        $admin = new User([
            'name' => $request->input('name') ?: 'Admin',
            'email' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);
        $admin->is_admin = true;
        $admin->save();
        Journal::noter('Administrateur ajouté', $admin->email);

        return redirect()->route('store', ['type' => 'admins'])->with('success', 'Administrateur ajouté avec succès.');
    }
}
