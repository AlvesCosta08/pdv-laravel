<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Imprimir QR Codes do Produto {{ $produto->nome }}</title>
    <style>
        @media print {
            body { margin: 0; }
        }
        .qrcode-container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 40px;
        }
        .qrcode-box {
            width: 45%;
            text-align: center;
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .qrcode-box svg {
            width: 128px;
            height: 128px;
        }
    </style>
</head>
<body onload="window.print()">

@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

    <div class="qrcode-container">
        @for ($i = 0; $i < 5; $i++)
            <div class="qrcode-box">
                <h3>{{ $produto->nome }} (ID: {{ $produto->id }})</h3>
                {!! QrCode::size(128)->generate($produto->id) !!}
            </div>
        @endfor
    </div>
</body>
</html>









