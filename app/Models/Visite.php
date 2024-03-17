<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    use HasFactory;

    protected $fillable = [
        'dateHeureDebut',
        'dateHeureFin',
        'raison_visite_id',
        'type_visite_id',
        'statut_id',
        'personnel_id',
        'visiteur_id',
        'details',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function typeVisite()
    {
        return $this->belongsTo(TypeVisite::class);
    }

    public function raisonVisite()
    {
        return $this->belongsTo(RaisonVisite::class);
    }

    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

    public function visiteur()
    {
        return $this->belongsTo(Visiteur::class);
    }
}
