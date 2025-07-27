<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pointage extends Model
{
    use HasFactory; use SoftDeletes;

    protected $fillable = [
        'user_id',
        'date_pointage',
        'heure_arrivee',
        'heure_depart',
        'justificatif_retard',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
