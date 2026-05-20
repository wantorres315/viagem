<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmigoPasseio extends Model
{
    use HasFactory;

    protected $table = 'amigo_passeios';
    
    protected $fillable = [
        'amigo_id',
        'passeio_id',
    ];

    // Relacionamentos
    public function amigo()
    {
        return $this->belongsTo(Pessoa::class, 'amigo_id');
    }

    public function passeio()
    {
        return $this->belongsTo(Passeio::class);
    }
}
