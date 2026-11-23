<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    protected $table = 'niveaux';

    protected $fillable = ['code', 'nom', 'ordre'];

    /** Années d'étude de chaque niveau, numérotées à la suite. */
    const ANNEES = [
        'licence' => [1, 2, 3],
        'master' => [4, 5],
        'doctorat' => [6, 7, 8],
    ];

    public static function libelleAnnee($annee)
    {
        return (int) $annee === 1 ? '1re année' : (int) $annee . 'e année';
    }

    /** [4 => '4e année', 5 => '5e année'] */
    public function getAnneesAttribute()
    {
        $annees = self::ANNEES[$this->code] ?? [];

        return array_combine($annees, array_map([self::class, 'libelleAnnee'], $annees));
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class)->orderBy('nom');
    }

    public function filieresActives()
    {
        return $this->filieres()->where('active', true);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre');
    }
}
