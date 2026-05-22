<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMala extends Model
{
    use HasFactory;

    protected $table = 'itemmalas';

    protected $fillable = [
        'mala_id',
        'pessoa_id',
        'item',
        'na_mala',
    ];
  

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function mala()
    {
        return $this->belongsTo(Mala::class);
    }
}
