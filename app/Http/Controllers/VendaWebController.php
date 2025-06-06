<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Venda;
use App\Models\ItemVenda;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VendaWebController extends Controller
{
    public function index()
    {
        $venda = session('venda', []);
        $itens = [];

        foreach ($venda as $produtoId => $item) {
            $produto = Produto::find($produtoId);
            if ($produto) {
                $itens[] = [
                    'produto_id' => $produto->id,
                    'nome' => $produto->nome,
                    'valor_venda' => $produto->valor_venda,
                    'quantidade' => $item['quantidade'],
                ];
            }
        }

        return view('pdv.index', compact('itens'));
    }

    public function buscar(Request $request)
    {
        $query = $request->get('q', '');

        $produtos = Produto::where('nome', 'like', "%{$query}%")
                    ->orWhere('codigo', 'like', "%{$query}%")
                    ->get(['id', 'nome', 'valor_venda']);

        return response()->json($produtos);
    }

    // Gerar comprovante de venda
        public function gerarComprovante($tipo = 'impressao')
    {
        $venda = session('venda', []);
        $itens = [];

        $total = 0;
        foreach ($venda as $produtoId => $item) {
            $produto = Produto::find($produtoId);
            if ($produto) {
                $itens[] = [
                    'produto_id' => $produto->id,
                    'nome' => $produto->nome,
                    'valor_venda' => $produto->valor_venda,
                    'quantidade' => $item['quantidade'],
                ];
                $total += $produto->valor_venda * $item['quantidade'];
            }
        }

        // Dados para a view
        $data = [
            'itens' => $itens,
            'total' => $total,
            'data' => now()->format('d/m/Y H:i:s'),
            'numero_pedido' => 'PDV-' . now()->format('YmdHis'),
            'empresa' => [
                'nome' => config('app.name', 'Minha Empresa'),
                'endereco' => 'Rua Exemplo, 123 - Centro',
                'telefone' => '(00) 1234-5678',
                'cnpj' => '12.345.678/0001-90'
            ]
        ];

        // Opção para PDF
        if ($tipo === 'pdf') {
            $pdf = Pdf::loadView('pdv.comprovante', $data);
            return $pdf->download('comprovante-'.$data['numero_pedido'].'.pdf');
        }

        // Retorna a view para impressão
        return view('pdv.comprovante', $data);
    }

    public function adicionarItem(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|integer',
            'quantidade' => 'required|integer|min:1',
        ]);

        // Validar se o produto existe no banco delivery via Model Produto
        if (!\App\Models\Produto::on('delivery')->where('id', $request->produto_id)->exists()) {
            return redirect()->back()->withErrors(['produto_id' => 'Produto inválido ou não encontrado.']);
        }

        $venda = session()->get('venda', []);

        $produtoId = $request->produto_id;
        $quantidade = $request->quantidade;

        if (isset($venda[$produtoId])) {
            $venda[$produtoId]['quantidade'] += $quantidade;
        } else {
            $venda[$produtoId] = [
                'produto_id' => $produtoId,
                'quantidade' => $quantidade,
            ];
        }

        session()->put('venda', $venda);

        return redirect()->route('pdv.index')->with('success', 'Produto adicionado.');
    }


    public function removerItem($produtoId)
    {
        $venda = session()->get('venda', []);

        // Remove o produto do carrinho
        unset($venda[$produtoId]);

        session()->put('venda', $venda);

        return redirect()->route('pdv.index');
    }

    public function finalizar()
    {
        $itens = session('venda', []);

        if (empty($itens)) {
            return redirect()->route('pdv.index')->with('error', 'Carrinho vazio!');
        }

        DB::beginTransaction();

        try {
            $venda = Venda::create([
                'data' => now(),
                'total' => 0, // será atualizado depois
            ]);

            $total = 0;

            foreach ($itens as $item) {
                $produto = Produto::findOrFail($item['produto_id']);
                $quantidade = $item['quantidade'];
                $valorUnitario = $produto->valor_venda;
                $subtotal = $valorUnitario * $quantidade;

                ItemVenda::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $quantidade,
                    'valor_unitario' => $valorUnitario,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $venda->update(['total' => $total]);

            DB::commit();

            session()->forget('venda');

            return redirect()->route('pdv.index')->with('success', 'Venda finalizada com sucesso!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('pdv.index')->with('error', 'Erro ao finalizar venda: ' . $e->getMessage());
        }
    }
}
