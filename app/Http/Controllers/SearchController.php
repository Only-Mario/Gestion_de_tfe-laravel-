<?php

namespace App\Http\Controllers;

use App\Models\Tfe;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $years = Tfe::years();
        $kw = $request->input('search'); 
        $tfes = [];
    
      if($kw!=null || $kw!=""){
        if (is_numeric($kw)) {
            $tfes = Tfe::searchByYear($kw)->get();
        }
        else if(str_starts_with($kw, '@_')){
            $kw = trim($kw, '@_');
            $tfes = Tfe::searchByEntity($kw)->get();
        }
        else{
            $tfes = Tfe::searchByTheme($kw)->get();
        }
        return view('search', compact('tfes', 'years', 'kw'));
    }else                                 {
        return view('search', compact('tfes', 'years', 'kw'));
    }
    }
}
