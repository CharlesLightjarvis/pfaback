<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'password',
        'email',
        'telephone',
        'poste',
    ];

    protected $hidden = [
        'password',
    ];

    public function visites()
    {
        return $this->hasMany(Visite::class);
    }

    public function compte()
    {
        return $this->hasOne(User::class);
    }
}
