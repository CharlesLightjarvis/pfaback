<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visiteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'type_visiteur_id'
    ];

    public function typeVisiteur()
    {
        return $this->belongsTo(TypeVisiteur::class);
    }

    public function visites()
    {
        return $this->hasMany(Visite::class);
    }
}
