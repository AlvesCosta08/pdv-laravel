<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();

        // Anexar os produtos manualmente para cada categoria
        foreach ($categorias as $categoria) {
            $categoria->produtos = Produto::where('categoria', $categoria->id)->get();
        }

        return response()->json($categorias);
    }

    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->produtos = Produto::where('categoria', $categoria->id)->get();

        return response()->json($categoria);
    }
}

