<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Routes
Route::get('/', ['as' => 'welcome', 'uses' => 'App\Http\Controllers\TfeController@index']);
Route::get('search', ['as' => 'search', 'uses' => 'App\Http\Controllers\SearchController@search']);

// Authentication Routes
Auth::routes();
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Admin Routes - Middleware: is_admin, auth
Route::group(['middleware' => ['is_admin', 'auth']], function () {

    // Password Management
    Route::get('/admin/changepassword', function () {
        return view('auth/passwords/updatePassword');
    })->name('changepasswordView');

    Route::post('/admin/otherPassword/{id}', 'App\Http\Controllers\passwordUpdateController@index')->name('passwordUpdate');

    // Student Management
    Route::get('/admin/delete/student/{id}', 'App\Http\Controllers\Admin\StudentController@delete')->name('delete_student');
    Route::post('/admin/add/student', 'App\Http\Controllers\Admin\StudentController@new')->name('addStudent');

    // Dashboard & Admin Management
    Route::get('/admin/dashboard', 'App\Http\Controllers\Admin\TfeController@showDashboard')->name('dashboard');
    Route::get('/admin/dashboard/{id}/{status}', 'App\Http\Controllers\Admin\myStatusController@index')->name('status');
    Route::get('/admin/store', 'App\Http\Controllers\Admin\TfeController@student')->name('store');

    Route::post('/admin/addadmin', function () {
        if (request('username') != null && request('password') != null) {
            if (password_verify(request('passwordV'), Auth::user()->password)) {
                User::create([
                    'email' => request('username'),
                    'password' => Hash::make(request('password')),
                    'name' => 'Admin',
                    'is_admin' => 1,
                ]);
            } else {
                return redirect()->route('store')->with('error', 'Mot de passe incorrect');
            }
        }
        return redirect(route('store'));
    })->name('addAdmin');
});

// Authenticated User Routes - Middleware: auth
Route::group(['middleware' => 'auth'], function () {

    // TFE Management
    Route::resource('tfe', "App\Http\Controllers\TfeController");
    Route::get("/profil/{id}", 'App\Http\Controllers\profilController@index')->name('profil');
    Route::get("/edit/{id}", 'App\Http\Controllers\TfeController@edit')->name('editTfe');
    Route::post("/update/", 'App\Http\Controllers\TfeController@update')->name('updateTfe');
    Route::get("/delete/{id}", 'App\Http\Controllers\TfeController@destroy')->name('tfeDelete');
});

