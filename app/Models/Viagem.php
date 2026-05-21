<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viagem extends Model
{
    use HasFactory;
    protected $table = 'viagens';
    protected $fillable = [
        'user_id',
        'nome',
        'data_ida',
        'data_volta',
        'budget',
    ];

    // Relacionamento: cada viagem pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
        // Relacionamento: uma viagem tem muitas pessoas
    public function pessoas()
    {
        return $this->hasMany(Pessoa::class);
    }

    // Relacionamento: uma viagem tem muitos destinos
    public function destinos()
    {
        return $this->hasMany(Destino::class);
    }

    // Relacionamento: uma viagem tem muitos itinerarios
    public function itinerarios()
    {
        return $this->hasMany(Itinerario::class);
    }

    public function malas()
    {
        return $this->hasMany(Mala::class);
    }

    public function amigos()
    {
        return $this->hasMany(Amigo::class);
    }

    public function checklist()
    {
        return $this->hasMany(ChecklistViagem::class);
    }
}
