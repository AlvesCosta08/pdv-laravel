<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendaWebController;
use App\Http\Controllers\Api\ProdutoQRController;

use Illuminate\Support\Facades\Route;

Route::get('/produtos/buscar', [VendaWebController::class, 'buscar'])->name('produtos.buscar');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/', function () {
    return redirect()->route('pdv.index');
});

// Rotas para o PDV (frente de caixa)
Route::prefix('pdv')->group(function () {
    Route::get('/', [VendaWebController::class, 'index'])->name('pdv.index');
    Route::get('/produtos/buscar', [VendaWebController::class, 'buscar'])->name('produtos.buscar');
    Route::post('/adicionar-item', [VendaWebController::class, 'adicionarItem'])->name('pdv.adicionarItem');
    Route::delete('/remover-item/{produtoId}', [VendaWebController::class, 'removerItem'])->name('pdv.removerItem');
    Route::post('/finalizar', [VendaWebController::class, 'finalizar'])->name('pdv.finalizar');
});

Route::get('/produtos', [ProdutoQRController::class, 'index'])->name('produtos.index');

// Imprimir QR Codes de um único produto

Route::get('/produtos/{id}/qrcode-print', [ProdutoQRController::class, 'gerarQRCodeProduto'])->name('produtos.qrcode.print');

// Imprimir QR Codes de todos os produtos
Route::get('/produtos/qrcodes', [ProdutoQRController::class, 'qrcodes'])->name('produtos.qrcodes');
Route::get('/produtos/qrcodes-list', [ProdutoQRController::class, 'qrcodesList'])->name('produtos.qrcodes-list');

Route::get('/comprovante/{tipo}', [VendaWebController::class, 'gerarComprovante'])->name('pdv.comprovante');



require __DIR__.'/auth.php';
