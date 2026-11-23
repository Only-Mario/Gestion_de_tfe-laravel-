<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'niveau_id', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function tfes()
    {
        return $this->hasMany(Tfe::class);
    }

    public function getLibelleAttribute()
    {
        return trim($this->description) . ' (' . $this->nom . ')';
    }
}
