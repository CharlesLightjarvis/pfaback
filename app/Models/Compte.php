<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'personnel_id',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
