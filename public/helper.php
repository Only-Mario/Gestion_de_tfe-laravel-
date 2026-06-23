<?php 
namespace App;
use App\Models\filieres;

if(!function_exists('myglobal')){
	function myglobal(){
      $F=filieres::all('nom');
       foreach ($F as $key=>$value) {
        $Filieres[]=$value['nom'];
       }
      return $Filieres;
	}
}
?>