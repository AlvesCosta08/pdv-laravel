<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $connection = 'delivery'; // nome da conexão (outro banco)
    protected $table = 'produtos';      // nome da tabela
    protected $primaryKey = 'id';       // chave primária
    public $timestamps = false;         // se a tabela não tem created_at e updated_at

    protected $fillable = [
        'nome', 'descricao', 'categoria', 'valor_compra', 'valor_venda',
        'estoque', 'foto', 'nivel_estoque', 'tem_estoque', 'ativo', 'url',
        'guarnicoes', 'promocao', 'combo', 'delivery', 'preparado', 'val_promocional'
    ];
}


