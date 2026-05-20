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
    ];

    
}
