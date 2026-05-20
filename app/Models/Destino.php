<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'data',
        'voo_id',
    ];

    // Relacionamento: destino pode ter um voo (opcional)
    public function voo()
    {
        return $this->belongsTo(Voo::class);
    }
}
