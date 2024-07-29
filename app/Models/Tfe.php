<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tfe extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'email_maitre_memoire',
        'maitre_memoire',
        'email_tuteur',
        'lieu_de_stage',
        'tuteur_de_stage',
        'groupe_pedagogique',
        'annee_de_realisation',
        'auteurs',
        'document_id',
        'user_id',
        'status',
    ];

    public function document()
    {
        return $this->hasOne('App\Models\Document');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected static function years(){
        $currentYear = date('Y');
        $years = [];
    
        while ($currentYear >= 2000) {
            array_push($years, $currentYear);
            $currentYear--;
        }
        return $years;
    }

    public function scopeSearchByTheme($query, $kw){
        $query->where('theme', 'LIKE', '%'. $kw .'%');
    }
    
    public function scopeSearchByYear($query, $year){
        $query->where('annee_de_realisation', $year);
    }
    
    public function scopeSearchByEntity($query, $groupe_pedagogique){
        $query->where('groupe_pedagogique', $groupe_pedagogique);
    }
    
    public function scopeOrderByDate($query){
        $query->orderBy('created_at', 'desc');
    }

    public function scopeSearchByKeyword($query, $kw){
        $query->where(function($query) use ($kw) {
            $query->where('theme', 'LIKE', '%'. $kw .'%')
                  ->orWhere('email_maitre_memoire', 'LIKE', '%'. $kw .'%')
                  ->orWhere('maitre_memoire', 'LIKE', '%'. $kw .'%')
                  ->orWhere('email_tuteur', 'LIKE', '%'. $kw .'%')
                  ->orWhere('lieu_de_stage', 'LIKE', '%'. $kw .'%')
                  ->orWhere('tuteur_de_stage', 'LIKE', '%'. $kw .'%')
                  ->orWhere('groupe_pedagogique', 'LIKE', '%'. $kw .'%')
                  ->orWhere('annee_de_realisation', 'LIKE', '%'. $kw .'%')
                  ->orWhere('auteurs', 'LIKE', '%'. $kw .'%');
        });
    }
}
