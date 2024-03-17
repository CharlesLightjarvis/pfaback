<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaisonVisite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    public function visites()
    {
        return $this->hasMany(Visite::class);
    }
}
