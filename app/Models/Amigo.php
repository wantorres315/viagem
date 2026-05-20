<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amigo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cidade',
    ];

    public function presentes()
    {
        return $this->hasMany(Presente::class);
    }

    public function passeios()
    {
        return $this->belongsToMany(Passeio::class, 'amigo_passeios');
    }
}
