@extends('layouts.app')

@section('content')
<div class="container py-4">

    <header class="mb-4">
        <h1 class="h3 fw-bold text-dark">Dashboard</h1>
    </header>

    <div class="bg-white p-4 rounded shadow-sm">
        <p>Bem-vindo ao painel de controle!</p>

        <div class="mt-4">
            <a href="{{ route('pdv.index') }}"
               class="btn btn-primary">
               Abrir Frente de Caixa (PDV)
            </a>
        </div>
    </div>

</div>
@endsection


