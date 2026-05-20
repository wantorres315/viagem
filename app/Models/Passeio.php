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
      public function pessoas()
    {
        return $this->belongsToMany(\App\Models\Pessoa::class, 'passeio_pessoas', 'passeio_id', 'pessoa_id')->withPivot('valor', 'data');
    }
    
}
