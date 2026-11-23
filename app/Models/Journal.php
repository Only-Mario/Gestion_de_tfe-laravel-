<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/** Journal des actions d'administration. */
class Journal extends Model
{
    protected $table = 'journal_admin';

    protected $fillable = ['user_id', 'auteur', 'action', 'details', 'ip'];

    public static function noter($action, $details = null)
    {
        return self::create([
            'user_id' => Auth::id(),
            'auteur' => Auth::user() ? Auth::user()->name : 'Système',
            'action' => $action,
            'details' => $details,
            'ip' => request()->ip(),
        ]);
    }
}
