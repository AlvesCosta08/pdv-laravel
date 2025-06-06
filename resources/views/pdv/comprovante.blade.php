<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Venda - {{ $numero_pedido }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 11px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        th, td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .total {
            font-weight: bold;
            font-size: 13px;
            text-align: right;
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $empresa['nome'] }}</h1>
        <p>{{ $empresa['endereco'] }}</p>
        <p>CNPJ: {{ $empresa['cnpj'] }} | Tel: {{ $empresa['telefone'] }}</p>
        <p>----------------------------------------</p>
        <p><strong>COMPROVANTE DE VENDA</strong></p>
        <p>Nº: {{ $numero_pedido }} | Data: {{ $data }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qtd</th>
                <th class="text-right">Unit.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td>{{ $item['nome'] }}</td>
                <td class="text-right">{{ $item['quantidade'] }}</td>
                <td class="text-right">R$ {{ number_format($item['valor_venda'], 2, ',', '.') }}</td>
                <td class="text-right">R$ {{ number_format($item['valor_venda'] * $item['quantidade'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL: R$ {{ number_format($total, 2, ',', '.') }}
    </div>

    <div class="footer">
        <p>Obrigado pela preferência!</p>
        <p>{{ $empresa['nome'] }}</p>
        <p>{{ config('app.url') }}</p>
    </div>

    <div class="no-print text-center" style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 5px 10px; margin: 0 5px; cursor: pointer;">
            🖨️ Imprimir
        </button>
        <a href="{{ route('pdv.comprovante', ['tipo' => 'pdf']) }}" style="padding: 5px 10px; margin: 0 5px; text-decoration: none; color: #000; border: 1px solid #000;">
            📄 Baixar PDF
        </a>
        <button onclick="window.close()" style="padding: 5px 10px; margin: 0 5px; cursor: pointer;">
            ❌ Fechar
        </button>
    </div>

    <script>
        // Focar em impressão automaticamente se for mobile
        if(window.innerWidth < 768) {
            window.print();
        }
    </script>
</body>
</html>
