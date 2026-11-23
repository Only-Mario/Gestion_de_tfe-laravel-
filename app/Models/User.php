<?php

namespace App\Models;

use App\Mail\MessageInsti;
use App\Services\Courriel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'study_year',
        'entity',
        'matricule',
        'niveau_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function tfe()
    {
        return $this->hasOne('App\Models\Tfe');
    }

    /**
     * Même circuit d'envoi que les autres messages (secours compris).
     * Lien bâti sur l'adresse officielle, jamais sur l'en-tête Host de la requête (falsifiable).
     */
    public function sendPasswordResetNotification($token)
    {
        $lien = rtrim(config('app.url'), '/') . route('password.reset', ['token' => $token, 'email' => $this->email], false);
        $envoye = Courriel::envoyer($this->email, new MessageInsti('Réinitialisation de votre mot de passe', 'Nouveau mot de passe', [
            "Bonjour {$this->name},",
            'Vous avez demandé à choisir un nouveau mot de passe. Ce lien est valable 60 minutes et ne sert qu’une fois.',
            'Si vous n’êtes pas à l’origine de cette demande, ignorez ce message : rien ne change.',
        ], null, ['Choisir un mot de passe', $lien]));

        if (!$envoye) {
            throw new \RuntimeException('Lien de réinitialisation non envoyé.');
        }
    }

    public static function normaliser($colonne, $valeur)
    {
        $valeur = trim((string) $valeur);

        return $colonne === 'email' ? mb_strtolower($valeur) : mb_strtoupper(preg_replace('/\s+/', '', $valeur));
    }

    /** Toute création ou modification passe par ici : la base refuse ensuite les doublons. */
    protected static function booted()
    {
        static::saving(function (User $user) {
            foreach (['email', 'matricule'] as $colonne) {
                if ($user->isDirty($colonne)) {
                    $valeur = self::normaliser($colonne, $user->$colonne);
                    $user->$colonne = $valeur === '' ? null : $valeur;
                    $user->{$colonne . '_cle'} = $user->$colonne;
                    // Une adresse modifiée (par un admin) doit être reconfirmée
                    if ($colonne === 'email' && $user->exists && !$user->isDirty('email_verified_at')) {
                        $user->email_verified_at = null;
                    }
                }
            }
        });
    }

    /** Comptes dont l'email ou le matricule appartient aussi à un compte plus ancien. */
    public function scopeEnDouble($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($q) {
                $q->whereNull('email_cle')->whereNotNull('email');
            })->orWhere(function ($q) {
                $q->whereNull('matricule_cle')->whereNotNull('matricule')->where('matricule', '<>', '');
            });
        });
    }

    public function tfes()
    {
        return $this->hasMany('App\Models\Tfe');
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
