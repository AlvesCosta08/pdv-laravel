<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $connection = 'delivery'; // banco externo
    protected $table = 'categorias';

    public $timestamps = false;

    protected $fillable = [
        'nome', 'descricao', 'foto', 'cor', 'ativo', 'url', 'mais_sabores', 'delivery',
    ];
}

