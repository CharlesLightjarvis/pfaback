<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVisiteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom'
    ];

    public function visiteurs()
    {
        return $this->hasMany(Visiteur::class);
    }
}
