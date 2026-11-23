<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'resume',
    ];

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOwnedOrAdministeredBy($user)
    {
        return $user !== null
            && ($user->is_admin == 1
                || ($this->user_id !== null && (int) $this->user_id === (int) $user->id));
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    /** Document déjà déposé par cet étudiant pour ce niveau. */
    public static function depotExistant($userId, $niveauId)
    {
        return self::with(['niveau', 'filiere'])
            ->where('user_id', $userId)
            ->where('niveau_id', $niveauId)
            ->first();
    }

    const TRIS = [
        'recent' => 'Plus récents',
        'ancien' => 'Plus anciens',
        'annee_desc' => 'Année, décroissante',
        'annee_asc' => 'Année, croissante',
        'filiere' => 'Filière, A à Z',
        'theme' => 'Thème, A à Z',
    ];

    public function scopeTrier($query, $tri)
    {
        switch ($tri) {
            case 'ancien': return $query->orderBy('created_at')->orderBy('id');
            case 'annee_desc': return $query->orderByDesc('annee_de_realisation')->orderByDesc('id');
            case 'annee_asc': return $query->orderBy('annee_de_realisation')->orderBy('id');
            case 'filiere': return $query->orderBy('groupe_pedagogique')->orderByDesc('annee_de_realisation')->orderByDesc('id');
            case 'theme': return $query->orderBy('theme')->orderBy('id');
            default: return $query->orderByDesc('created_at')->orderByDesc('id');
        }
    }

    /** Filtres communs à l'accueil, à la recherche et à l'administration. */
    public function scopeFiltrer($query, array $filtres)
    {
        foreach (['niveau' => 'niveau_id', 'filiere' => 'filiere_id', 'annee' => 'annee_de_realisation'] as $cle => $colonne) {
            if (!empty($filtres[$cle])) {
                $query->where($colonne, $filtres[$cle]);
            }
        }
        if (!empty($filtres['kw'])) {
            $kw = '%' . addcslashes($filtres['kw'], '%_') . '%';
            $query->where(function ($q) use ($kw) {
                foreach (['theme', 'auteurs', 'maitre_memoire', 'tuteur_de_stage', 'lieu_de_stage'] as $colonne) {
                    $q->orWhere($colonne, 'LIKE', $kw);
                }
            });
        }

        return $query;
    }

    public function getStatutLibelleAttribute()
    {
        return [0 => 'En attente', 1 => 'Publié', 2 => 'Rejeté'][(int) $this->status] ?? 'Inconnu';
    }

    public function getUrlPubliqueAttribute()
    {
        return route('rapport', ['id' => $this->id, 'slug' => Str::limit(Str::slug($this->theme), 80, '') ?: 'rapport']);
    }

    public function modifications()
    {
        return $this->hasMany(TfeModification::class)->latest('id');
    }

    /** L'auteur peut modifier (avec justificatif) son tfe en attente ou publié, mais pas rejeté. */
    public function isEditableBy($user)
    {
        return $this->isOwnedOrAdministeredBy($user)
            && ($user->is_admin == 1 || in_array((int) $this->status, [0, 1], true));
    }

    /** Seul un administrateur peut supprimer un tfe. */
    public function isDeletableBy($user)
    {
        return $user !== null && $user->is_admin == 1;
    }

    public static function annee_de_realisations(){
        $currentYear = date('Y');
        $annee_de_realisations = [];
    
        while ($currentYear >= 2000) {
            array_push($annee_de_realisations, $currentYear);
            $currentYear--;
        }
        return $annee_de_realisations;
    }

    public function scopeOrderByDate($query){
        $query->orderBy('created_at', 'desc');
    }
}
