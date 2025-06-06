<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUPOM ROMANEIO - {{ $numero_pedido }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            margin: 0;
            padding: 2mm;
            color: #000;
            width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 2mm;
            padding-bottom: 1mm;
            border-bottom: 1px dashed #000;
        }
        .header h1 {
            font-size: 12px;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 1mm 0;
            font-size: 9px;
            line-height: 1.1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
            font-size: 9px;
        }
        th, td {
            padding: 1mm 0.5mm;
            border-bottom: 1px dotted #000;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 2mm;
            border-top: 1px solid #000;
            padding-top: 1mm;
            font-size: 11px;
        }
        .footer {
            margin-top: 3mm;
            text-align: center;
            font-size: 8px;
            border-top: 1px dashed #000;
            padding-top: 1mm;
            line-height: 1.1;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider {
            border-top: 1px dashed #000;
            margin: 1mm 0;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
            html, body { width: 80mm; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ strtoupper($empresa['nome']) }}</h1>
        <p>{{ $empresa['endereco'] }}</p>
        <p>CNPJ: {{ $empresa['cnpj'] }} | IE: {{ $empresa['ie'] ?? 'ISENTO' }}</p>
        <p>TEL: {{ $empresa['telefone'] }} | {{ $empresa['horario_funcionamento'] ?? '' }}</p>
        <div class="divider"></div>
        <p><strong>CUPOM ROMANEIO</strong></p>
        <p>Nº: {{ $numero_pedido }} | {{ $data }} {{ date('H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>COD</th>
                <th>DESC</th>
                <th class="text-right">QTD</th>
                <th class="text-right">UN</th>
                <th class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td>{{ $item['produto_id'] }}</td>
                <td>{{ substr($item['nome'], 0, 15) }}</td>
                <td class="text-right">{{ $item['quantidade'] }}</td>
                <td class="text-right">{{ number_format($item['valor_venda'], 2, ',', '') }}</td>
                <td class="text-right">{{ number_format($item['valor_venda'] * $item['quantidade'], 2, ',', '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL R$ {{ number_format($total, 2, ',', '.') }}
    </div>

    <div class="divider"></div>
    <p style="text-align: center; font-size: 9px; margin: 1mm 0;">
        FORMA PAGAMENTO: {{ $forma_pagamento ?? 'DINHEIRO' }}
    </p>
    <div class="divider"></div>

    <div class="footer">
        <p>VOLTE SEMPRE!</p>
        <p>{{ $empresa['nome'] }}</p>
        <p>{{ date('d/m/Y H:i:s') }} | OPERADOR: {{ $operador ?? 'SISTEMA' }}</p>
        <p>----------------------------------------</p>
        <p>CONSULTE PELA CHAVE DE ACESSO EM</p>
        <p>{{ $empresa['site_nf'] ?? '' }}</p>
    </div>

    <div class="no-print text-center" style="margin-top: 3mm;">
        <button onclick="window.print()" style="padding: 2mm; margin: 0 1mm; cursor: pointer; font-size: 9px;">
            IMPRIMIR
        </button>
        <button onclick="window.close()" style="padding: 2mm; margin: 0 1mm; cursor: pointer; font-size: 9px;">
            FECHAR
        </button>
    </div>

    <script>
        // Auto-print em dispositivos móveis
        if(/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
            setTimeout(function(){ window.print(); }, 200);
        }
    </script>
</body>
</html>
