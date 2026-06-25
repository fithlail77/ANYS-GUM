<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label Asset</title>
    <script src="{{ asset('sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        @page {
            size: 50mm 30mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: white;
        }
        .label-container {
            width: 50mm;
            height: 30mm;
            page-break-after: always;
            padding: 1.5mm 2mm;
            box-sizing: border-box;
            overflow: hidden;
            position: relative;
        }
        .label-header {
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 0.5mm;
            line-height: 1.2;
        }
        .label-sub {
            font-size: 7px;
            text-align: center;
            margin-bottom: 0.3mm;
            line-height: 1.1;
        }
        .code-display {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0.5mm 0;
        }
        svg.barcode {
            width: 100%;
            max-width: 46mm;
            height: 12mm;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding: 15px; background: #333; color: white; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-weight: bold; cursor: pointer;">KLIK DISINI UNTUK PRINT</button>
        <p style="font-size: 12px; margin-top: 5px;">Pastikan setting Paper Size di browser adalah 50x30mm</p>
    </div>

    @foreach($assets as $asset)
    <div class="label-container">
        <div class="label-header">{{ $asset->asset_number }}</div>
        <div class="label-sub">SAP: {{ $asset->asset_sap_code }}</div>
        <div class="label-sub">{{ \Illuminate\Support\Str::limit($asset->asset_name, 30) }}</div>
        @if($asset->serial_number)
        <div class="label-sub">SN: {{ $asset->serial_number }}</div>
        @endif
        <div class="label-sub">Pengguna: {{ $asset->current_owner }}</div>
        <div class="code-display">
            @if($type == 'barcode')
                <svg class="barcode" id="barcode-{{ $asset->id }}"></svg>
            @else
                <div id="qrcode-{{ $asset->id }}"></div>
            @endif
        </div>
    </div>
    @endforeach

    <script>
        $(document).ready(function() {
            @foreach($assets as $asset)
                @if($type == 'barcode')
                    JsBarcode("#barcode-{{ $asset->id }}", "{{ $asset->asset_number }}", {
                        format: "CODE128",
                        width: 1.2,
                        height: 40,
                        displayValue: false,
                        margin: 0
                    });
                @else
                    new QRCode(document.getElementById("qrcode-{{ $asset->id }}"), {
                        text: "{{ $asset->asset_number }}",
                        width: 50,
                        height: 50,
                        correctLevel: QRCode.CorrectLevel.M
                    });
                @endif
            @endforeach
        });
    </script>
</body>
</html>