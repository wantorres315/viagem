<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasseioPessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'passeio_id',
        'pessoa_id',
        'valor',
        'data',
    ];

    // Relacionamentos
    public function passeio()
    {
        return $this->belongsTo(Passeio::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
