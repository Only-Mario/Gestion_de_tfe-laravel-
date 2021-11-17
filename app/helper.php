<?php 

use App\Models\filieres;
use App\Models\Tfe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if(!function_exists("myglobal")){
            function myglobal(){
            $Filieres=filieres::all('nom');
             return;     
      }
    }
     if(!function_exists("has_tfe")){
            function has_tfe(){
            $tfe=Tfe::where("user_id",'=',Auth::user()->id)->first();
            
            return $tfe;
       }
     }
?>