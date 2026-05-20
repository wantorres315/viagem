<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presente extends Model
{
    use HasFactory;

    protected $fillable = [
        'amigo_id',
        'presente',
        'mala_id',
    ];

    // Relacionamentos
    public function amigo()
    {
        return $this->belongsTo(Amigo::class);
    }
}
