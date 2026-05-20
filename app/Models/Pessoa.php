<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'idade',
        'viagem_id',
    ];

    // Relacionamento: cada pessoa pertence a uma viagem
    public function viagem()
    {
        return $this->belongsTo(Viagem::class);
    }

    public function malas()
    {
        return $this->hasMany(Mala::class);
    }
}
