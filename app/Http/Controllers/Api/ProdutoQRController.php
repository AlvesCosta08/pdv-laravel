<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;

class ProdutoQRController extends Controller
{
    public function index()
    {
        $produtos = Produto::paginate(10);
        return view('produtos.index', compact('produtos'));
    }


    // Imprimir QR Codes de um produto único
    public function gerarQRCodeProduto($id)
    {
        $produto = Produto::findOrFail($id);

        return view('produtos.qrcode-single', compact('produto'));
    }

    // Imprimir QR Codes de todos os produtos
    public function qrcodes()
    {
        $produtos = Produto::all();
        return view('produtos.qrcodes', compact('produtos'));
    }
    public function qrcodesList()
    {
        $produtos = Produto::paginate(10); // ou all() dependendo do seu caso
        return view('produtos.qrcodes-list', compact('produtos'));
    }
}






