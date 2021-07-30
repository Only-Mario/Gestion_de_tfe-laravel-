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
        'matricule',
        'document_id',
        'user_id',
        'status',
    ];

    public function document()
    {
        return $this->hasOne('App\Models\Document');
    }


    protected static function years(){
        $increment = 2000;
        $years = [];

        while ($increment <= date('Y')) {

            array_push($years, $increment);
            $increment++;
        }

        return $years;
    }


    public function scopeSearchByTheme($query, $word){
        $query->where('theme', 'LIKE', '%'. $word .'%');
    }
    
    public function scopeSearchByYear($query, $year){
        $query->where('annee_de_realisation', $year);
    }
    
    public function scopeSearchByEntity($query, $q){
        $query->where('groupe_pedagogique', $q);
    }
    
    public function scopeOrderByDate($query){
        $query->orderBy('created_at', 'desc');
    }


}
