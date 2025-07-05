@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary">Lista de Produtos</h2>
        <h2 class="mb-4 text-primary d-flex justify-content-between align-items-center">

        <a href="{{ route('produtos.qrcode.print.todos') }}" target="_blank" class="btn btn-success">
            Imprimir Todos os QR Codes
        </a>
    </h2>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 5%;">ID</th>
                    <th scope="col" style="width: 40%;">Nome</th>
                    <th scope="col" style="width: 35%;">Categoria</th>
                    <th scope="col" class="text-center" style="width: 20%;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                    <tr>
                        <td>{{ $produto->id }}</td>
                        <td class="fw-semibold">{{ $produto->nome }}</td>
                        <td>{{ optional($produto->categoria_detalhes)->nome ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('produtos.qrcode.print', $produto->id) }}" target="_blank"
                               class="btn btn-sm btn-primary">
                               Imprimir QR Codes
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $produtos->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection





