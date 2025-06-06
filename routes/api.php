<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\ProdutoQRController;

Route::prefix('v1')->group(function () {
    // Categorias
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

    // Produtos
    Route::get('/produtos', [ProdutoController::class, 'index']);
    Route::get('/produtos/buscar', [ProdutoController::class, 'buscar']); // <-- deve vir antes
    Route::get('/produtos/{id}', [ProdutoController::class, 'show']);


});

