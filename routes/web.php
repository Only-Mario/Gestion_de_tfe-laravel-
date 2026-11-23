<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

// Public Routes
Route::get('/', 'App\Http\Controllers\TfeController@index')->name('welcome');
// Ancienne adresse de recherche, conservée pour les liens existants
Route::get('search', 'App\Http\Controllers\SearchController@search')->name('search');
Route::get('/rapports/{id}/{slug?}', 'App\Http\Controllers\TfeController@rapport')->where('id', '[0-9]+')->name('rapport');
Route::get('/sitemap.xml', 'App\Http\Controllers\SitemapController@index')->name('sitemap');
Route::get('/verify/{reference}/{token}', 'App\Http\Controllers\TfeController@verifyAttestation')->middleware('throttle:30,1,l1')->name('tfe.verify');

// Authentication Routes
Route::middleware('throttle:30,1,l2')->group(function () {
    Auth::routes();
});
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Style, script et logo (voir RessourceController)
Route::get('/ressources/{version}/{nom}', 'App\Http\Controllers\RessourceController@show')->withoutMiddleware('web')->name('ressource');

// Raccourcis
Route::redirect('/login/admin', '/login');
Route::redirect('/admin', '/admin/dashboard');

// Admin Routes - Middleware: auth, is_admin
Route::group(['middleware' => ['auth', 'is_admin']], function () {

    // Justificatif joint par un étudiant à une modification
    Route::get('/admin/justificatifs/{id}', 'App\Http\Controllers\TfeController@justificatif')->name('justificatif');
    Route::post('/admin/modifications/{id}/restaurer', 'App\Http\Controllers\Admin\myStatusController@restaurer')->name('restaurer');

    // Filières
    Route::get('/admin/filieres', 'App\Http\Controllers\Admin\FiliereController@index')->name('filieres');
    Route::post('/admin/filieres', 'App\Http\Controllers\Admin\FiliereController@store')->name('filieres.store');
    Route::put('/admin/filieres/{id}', 'App\Http\Controllers\Admin\FiliereController@update')->name('filieres.update');
    Route::post('/admin/filieres/{id}/basculer', 'App\Http\Controllers\Admin\FiliereController@basculer')->name('filieres.basculer');
    Route::delete('/admin/filieres/{id}', 'App\Http\Controllers\Admin\FiliereController@destroy')->name('filieres.destroy');

    // Student & Admin Management
    Route::post('/admin/students', 'App\Http\Controllers\Admin\StudentController@new')->name('addStudent');
    Route::post('/admin/students/{id}', 'App\Http\Controllers\Admin\StudentController@update')->name('update_student');
    Route::post('/admin/users/{id}/lien', 'App\Http\Controllers\Admin\StudentController@envoyerLien')->name('envoyerLien');
    Route::post('/admin/users/{id}/delete', 'App\Http\Controllers\Admin\StudentController@delete')->name('delete_student');
    Route::post('/admin/admins', 'App\Http\Controllers\Admin\AdminController@store')->middleware('throttle:10,1,l3')->name('addAdmin');

    // Dashboard & TFE moderation
    Route::get('/admin/dashboard', 'App\Http\Controllers\Admin\TfeController@showDashboard')->name('dashboard');
    Route::post('/admin/dashboard/{id}/{status}', 'App\Http\Controllers\Admin\myStatusController@index')->name('status');
    Route::get('/admin/export', 'App\Http\Controllers\Admin\TfeController@export')->name('export');
    Route::post('/admin/test-email', 'App\Http\Controllers\Admin\TfeController@testEmail')->middleware('throttle:5,10,l4')->name('testEmail');
    Route::get('/admin/journal', 'App\Http\Controllers\Admin\TfeController@journal')->name('journal');
    Route::get('/admin/store', 'App\Http\Controllers\Admin\TfeController@student')->name('store');
});

// Authenticated User Routes - Middleware: auth
Route::group(['middleware' => 'auth'], function () {

    // Mot de passe (étudiants et administrateurs)
    Route::view('/mot-de-passe', 'auth.passwords.updatePassword')->name('changepasswordView');
    Route::post('/mot-de-passe', 'App\Http\Controllers\passwordUpdateController@index')->middleware('throttle:6,1,l5')->name('passwordUpdate');

    Route::get('/mot-de-passe/code', 'App\Http\Controllers\passwordUpdateController@code')->name('password.code');
    Route::post('/mot-de-passe/code', 'App\Http\Controllers\passwordUpdateController@confirmer')->middleware('throttle:10,1,l6')->name('password.code.verifier');

    // Adresse email
    Route::get('/mon-email', 'App\Http\Controllers\EmailController@show')->name('email.confirmer');
    Route::post('/mon-email/envoyer', 'App\Http\Controllers\EmailController@envoyer')->middleware('throttle:10,1,l7')->name('email.envoyer');
    Route::post('/mon-email/changer', 'App\Http\Controllers\EmailController@changer')->middleware('throttle:10,1,l8')->name('email.changer');
    Route::post('/mon-email', 'App\Http\Controllers\EmailController@confirmer')->middleware('throttle:10,1,l9')->name('email.verifier');

    // TFE Management
    Route::resource('tfe', "App\Http\Controllers\TfeController")->except(['index', 'store']);
    Route::post('/tfe', 'App\Http\Controllers\TfeController@store')->middleware('throttle:15,1,l10')->name('tfe.store');
    Route::get('/mon-profil', 'App\Http\Controllers\profilController@edit')->name('profil.edit');
    Route::post('/mon-profil', 'App\Http\Controllers\profilController@update')->middleware('throttle:15,1,l11')->name('profil.update');
    Route::get("/profil/{id?}", 'App\Http\Controllers\profilController@index')->name('profil');
    Route::get("/edit/{id}", 'App\Http\Controllers\TfeController@edit')->name('editTfe');
    Route::post("/update/", 'App\Http\Controllers\TfeController@update')->middleware('throttle:15,1,l12')->name('updateTfe');
    Route::delete("/delete/{id}", 'App\Http\Controllers\TfeController@destroy')->name('tfeDelete');
    Route::post('/tfe/{id}/recu', 'App\Http\Controllers\TfeController@envoyerRecuParEmail')->middleware('throttle:5,10,l13')->name('tfe.recu');
    Route::get('/tfe/{id}/attestation', 'App\Http\Controllers\TfeController@downloadAttestation')->name('tfe.attestation');
});
