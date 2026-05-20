<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'data',
        'viagem_id',
        'passeio_id',
        // Adicione outros campos conforme necessário
    ];

    // Relacionamento: um itinerário tem muitos passeios
    public function passeios()
    {
        return $this->hasMany(\App\Models\Passeio::class);
    }
}
