<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mala extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'track',
        'viagem_id',
    ];

    public function viagem()
    {
        return $this->belongsTo(Viagem::class);
    }
}
