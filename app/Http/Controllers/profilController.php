<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\User;
use App\Models\Tfe;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class profilController extends Controller
{
    function index($id){
      $status='';
      $tfe=null;
      if($id!=-1){
         $tfe=Tfe::find($id);
      }
      if($tfe!=null){
            if($tfe->status==1){
            $status='Validé';
          } elseif($tfe->status==2){
             $status='Rejetté';
          } 
          else{
             $status='En attente';
          }
       }
       $user=Auth::user();
     
        return view('auth.profil',['user'=>$user,'tfe'=>$tfe,'status'=>$status]);
    }
}
