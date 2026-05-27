<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MalaFoto extends Model
{
    use HasFactory;

    protected $table = 'mala_foto';

    protected $fillable = [
        'mala_id',
        'caminho',
    ];

    public function mala()
    {
        return $this->belongsTo(Mala::class);
    }
}
