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
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1mm 2mm;
            box-sizing: border-box;
            overflow: hidden;
        }
        .asset-number {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 1mm;
        }
        .code-display {
            margin: 1mm 0;
            display: flex;
            justify-content: center;
            width: 100%;
        }
        svg.barcode {
            max-width: 42mm; /* Dibatasi agar tidak menyentuh tepi kertas 50mm */
            height: 15mm;   /* Tinggi proporsional untuk kertas 30mm */
        }
        .asset-name {
            font-size: 8px;
            margin-top: 1mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
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
        <div class="asset-number">{{ $asset->asset_number }}</div>
        <div class="code-display">
            @if($type == 'barcode')
                <svg class="barcode" id="barcode-{{ $asset->id }}"></svg>
            @else
                <div id="qrcode-{{ $asset->id }}"></div>
            @endif
        </div>
        <div class="asset-name">{{ $asset->asset_name }}</div>
    </div>
    @endforeach

    <script>
        $(document).ready(function() {
            @foreach($assets as $asset)
                @if($type == 'barcode')
                    JsBarcode("#barcode-{{ $asset->id }}", "{{ $asset->asset_number }}", {
                        format: "CODE128",
                        width: 1.5,     /* Diperkecil dari 2 ke 1.5 agar tidak terlalu lebar */
                        height: 50,      /* Tinggi barcode diatur agar pas */
                        displayValue: false,
                        margin: 5        /* Memberikan sedikit ruang aman */
                    });
                @else
                    new QRCode(document.getElementById("qrcode-{{ $asset->id }}"), {
                        text: "{{ $asset->asset_number }}",
                        width: 70,
                        height: 70,
                        correctLevel : QRCode.CorrectLevel.M
                    });
                @endif
            @endforeach
        });
    </script>
</body>
</html>