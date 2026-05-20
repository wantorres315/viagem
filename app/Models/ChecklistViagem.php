<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistViagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'viagem_id',
        'tarefa',
        'concluido',
    ];

    public function viagem()
    {
        return $this->belongsTo(Viagem::class);
    }
}
