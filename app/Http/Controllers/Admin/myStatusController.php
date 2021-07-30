<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tfe;
use App\Http\Controllers\TfeController as tfeController;
class myStatusController extends Controller
{

   //  ******************************************
   //          Les paramettres
   //          ** id ** {
   //          tfe->id ou -1 pour tout les tfe
   //          -2 pour les tfe non validés à supprimer 
   //         }
   //          ** ststus **{
   //          0 pour un tfe en attente 
   //          1 pour un tfe validé
   //          2 pour un tfe rejetté
   //         }
   // *******************************************
    function index($id,$status){
        if($id==-2){
            $tfe=Tfe::where(['status'=>2])->get();
            foreach($tfe as $one){
              tfeController::destroy($one['id'],true);
            }

        }
        else if($id==-1){
            $tfes=Tfe::where(['status'=>0])->get();
            foreach($tfes as $tfe){
                $tfe->update(['status'=>$status]); 
            }
        }
        else{
        $tfe=Tfe::find($id);
        $tfe->update(['status'=>$status]); 
        }
        $tfes=Tfe::all();
        return redirect(route('dashboard'));
    }
}
