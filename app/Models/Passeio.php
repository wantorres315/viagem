<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passeio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'valor_adulto',
        'valor_crianca',
        'itinerario_id',
    ];

    // Relacionamento: passeio pertence a um itinerário
    public function itinerario()
    {
        return $this->belongsTo(\App\Models\Itinerario::class);
    }
}
