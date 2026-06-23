<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
    ];

    public function tfe(){
        return $this->belongsTo('App\Models\Tfe');
    }

    public static function getName($request){
        // Generate file name
        $name = strtoupper($request->input('theme'));
        $name = str_replace(' ', '_', $name);
        $name = $name. '.pdf'; 
        return $name;
    }
}
