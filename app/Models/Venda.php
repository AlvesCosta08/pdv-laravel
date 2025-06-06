<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $fillable = ['data', 'total'];

    public function itens()
    {
        return $this->hasMany(ItemVenda::class);
    }
}

