<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Imprimir QR Codes do Produto {{ $produto->nome }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 10mm 10mm 10mm;
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
            flex-wrap: nowrap; /* linha única */
            justify-content: space-between;
            padding: 0;
            box-sizing: border-box;
        }

        .qrcode-box {
            flex: 0 0 auto;
            width: 18%; /* 5 QR codes na linha */
            display: flex;
            flex-direction: column;
            align-items: center;
            page-break-inside: avoid;
            white-space: normal;
            text-align: center;
        }

        .qrcode-box svg {
            width: 40px;
            height: 40px;
            margin-bottom: 6px;
        }

        .qrcode-box h3 {
            font-size: 0.45rem;
            margin: 0;
            line-height: 1.2;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="qrcode-container">
        @for ($i = 0; $i < 5; $i++)
            <div class="qrcode-box">
                {!! QrCode::size(40)->generate($produto->id) !!}
                <h3>{{ $produto->nome }}<br />ID: {{ $produto->id }}</h3>
            </div>
        @endfor
    </div>
</body>
</html>





