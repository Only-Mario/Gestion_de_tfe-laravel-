<?php 
namespace App;
use App\Models\filieres;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use App\Models\Document;
use App\Models\Tfe;

	function myglobal():array{
      $F=filieres::all('nom');
       foreach ($F as $key=>$value) {
        $Filieres[]=$value['nom'];
       }
      return $Filieres;
	}

?>