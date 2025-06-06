<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Verifica se está em modo de manutenção
if (file_exists(__DIR__.'/storage/framework/maintenance.php')) {
    require __DIR__.'/storage/framework/maintenance.php';
}

// Carrega o autoloader do Composer
require __DIR__.'/vendor/autoload.php';

// Inicializa a aplicação e lida com a requisição
(require __DIR__.'/bootstrap/app.php')
    ->handleRequest(Request::capture());


