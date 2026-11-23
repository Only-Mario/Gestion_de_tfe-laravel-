<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    /** Nom unique et sans caractère spécial, pour éviter écrasements et sous-dossiers. */
    public static function generateFileName($theme){
        return trim(Str::limit(Str::slug($theme), 100, '') . '-' . Str::uuid(), '-') . '.pdf';
    }

    /** D'anciens dépôts peuvent partager le même fichier : ne pas le supprimer dans ce cas. */
    public static function deleteFileIfUnused($path){
        if ($path && !self::where('path', $path)->exists()
            && !TfeModification::where('ancien_document_path', $path)->exists()) {
            Storage::delete($path);
        }
    }
}
