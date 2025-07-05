<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Imprimir QR Codes de Todos os Produtos</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
            }
        }

        body {
            font-family: Arial, sans-serif;
        }

        .qrcode-container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            box-sizing: border-box;
        }

        .qrcode-box {
            flex: 0 0 18%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 8mm;
            page-break-inside: avoid;
            text-align: center;
        }

        .qrcode-box svg {
            width: 30px;
            height: 30px;
            margin-bottom: 4px;
        }

        .qrcode-box h3 {
            font-size: 0.35rem;
            margin: 0;
            line-height: 1.1;
            word-break: break-word;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="qrcode-container">
        @foreach ($produtos as $produto)
            @for ($i = 0; $i < 5; $i++) {{-- 5 etiquetas por produto --}}
                <div class="qrcode-box">
                    {!! QrCode::size(30)->generate(route('produtos.qrcode.print', $produto->id)) !!}
                    <h3>{{ $produto->nome }}<br />ID: {{ $produto->id }}</h3>
                </div>
            @endfor
        @endforeach
    </div>
</body>
</html>


