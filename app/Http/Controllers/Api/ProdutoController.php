<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    // Retorna todos os produtos
    public function index()
    {
        $produtos = Produto::all();

        // Anexa categoria ao produto (opcional)
        foreach ($produtos as $produto) {
            $produto->categoria_detalhes = Categoria::find($produto->categoria);
        }

        return response()->json($produtos);
    }

    // Busca produto pelo nome
    public function buscar(Request $request)
    {
        $q = $request->query('q');

        $produtos = Produto::where('nome', 'like', "%{$q}%")->get();

        // Anexa categoria ao produto (opcional)
        foreach ($produtos as $produto) {
            $produto->categoria_detalhes = Categoria::find($produto->categoria);
        }

        return response()->json($produtos);
    }

    // Retorna produto por ID
    public function show($id)
    {
        // Sanitiza e valida o ID
        $idLimpo = ltrim($id, '0');

        if (!is_numeric($idLimpo)) {
            return response()->json(['error' => 'ID inválido'], 400);
        }

        $produto = Produto::find((int) $idLimpo);

        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        $produto->categoria_detalhes = Categoria::find($produto->categoria);

        return response()->json($produto);
    }

}




