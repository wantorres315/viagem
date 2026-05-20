<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMala extends Model
{
    use HasFactory;

    protected $fillable = [
        'pessoa_id',
        'item',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
