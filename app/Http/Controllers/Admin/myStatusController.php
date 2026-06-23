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
  //            -3 pour supprimer un tfe
   //         }
   //          ** ststus **{
   //          0 pour un tfe en attente 
   //          1 pour un tfe validé
   //          2 pour un tfe rejetté
   //         }
   // *******************************************
    function index($id,$status){
        // faire la meme action sur les tfe en attentes
        if($id==-1){
            $tfes=Tfe::where(['status'=>0])->get();
            foreach($tfes as $tfe){
                $tfe->update(['status'=>$status]); 
            }
        }
        // supprimer un tfe
        else if ($status==-3) {
            $tfe=Tfe::find($id);
            tfeController::destroy($tfe->id,true);
        }
        // supprimer les tfe rejettés
        else if($status==-2){
            $tfes=Tfe::where(['status'=>2])->get();
            foreach($tfes as $one){
              tfeController::destroy($one['id'],true);
            }

        }
 
        // valider un tfe ou le rejetter
        else{
            $tfe=Tfe::find($id);
            $tfe->update(['status'=>$status]); 
        }
        return redirect(route('dashboard'));
    }
}