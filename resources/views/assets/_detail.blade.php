<div class="container-fluid">
    <div class="row">
        {{-- Kolom Kiri: Detail Teks --}}
        <div class="col-md-8">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th style="width: 30%;">Nomor Aset</th>
                        <td>{{ $asset->asset_number }}</td>
                    </tr>
                    <tr>
                        <th>Nomor SAP Aset</th>
                        <td>{{ $asset->asset_sap_code }}</td>
                    </tr>
                    <tr>
                        <th>Nama Aset</th>
                        <td>{{ $asset->asset_name }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi / Spesifikasi</th>
                        <td>{{ $asset->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $asset->category }}</td>
                    </tr>
                    <tr>
                        <th>Pemilik Aset (Departemen)</th>
                        <td>{{ $asset->asset_owner }}</td>
                    </tr>
                    <tr>
                        <th>Pengguna Saat Ini</th>
                        <td>{{ $asset->current_owner }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $asset->location }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Perolehan</th>
                        <td>{{ \Carbon\Carbon::parse($asset->acquisition_date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Biaya Perolehan</th>
                        <td>Rp {{ number_format($asset->acquisition_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Buku (Depresiasi)</th>
                        <td>Rp {{ number_format($asset->depreciation, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Kondisi</th>
                        <td>
                            @if($asset->condition == 'Baik')
                                <span class="badge badge-success">{{ $asset->condition }}</span>
                            @else
                                <span class="badge badge-danger">{{ $asset->condition }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Kolom Kanan: Foto dan Barcode --}}
        <div class="col-md-4 text-center">
            <strong>Foto Aset</strong>
            @if($asset->photo_path)
                <img src="{{ asset('storage/' . $asset->photo_path) }}" alt="Foto Aset" class="img-fluid img-thumbnail mt-2 mb-4" style="max-height: 250px;">
            @else
                <p class="text-muted mt-2 mb-4">(Tidak ada foto)</p>
            @endif

            <strong>Barcode</strong>
            <div class="mt-2">
                <svg id="barcode"></svg>
            </div>

            <strong class="d-block mt-4">QR Code</strong>
            <div id="qrcode" class="mt-2 d-flex justify-content-center"></div>
        </div>
    </div>
</div>

<script>
    // Generate barcode
    JsBarcode("#barcode", "{{ $asset->asset_number }}", {
        format: "CODE128",
        lineColor: "#000",
        width: 2,
        height: 60,
        displayValue: true,
        fontSize: 16
    });

    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $asset->asset_number }}",
        width: 100,
        height: 100,
        correctLevel : QRCode.CorrectLevel.H
    });
</script>

{{-- Riwayat Aset --}}
<hr>
<h5 class="mt-4 mb-3">Riwayat Aset</h5>
<ul class="timeline">
    @forelse($asset->histories as $history)
        <li>
            <div class="timeline-badge"><i class="fa fa-history"></i></div>
            <div class="timeline-panel">
                <div class="timeline-heading">
                    <h6 class="timeline-title">
                        @if($history->previous_owner === '-')
                            Perolehan Aset Baru
                        @else
                            Pindah Tangan
                        @endif
                    </h6>
                    <p><small class="text-muted"><i class="fa fa-clock"></i> {{ \Carbon\Carbon::parse($history->transfer_date)->format('d F Y') }}</small></p>
                </div>
                <div class="timeline-body">
                    <p>
                        @if($history->previous_owner !== '-')
                            Dari: <strong>{{ $history->previous_owner }}</strong><br>
                        @endif
                        Ke: <strong>{{ $history->new_owner }}</strong>
                    </p>
                    @if($history->notes && $history->notes !== 'Perolehan Aset Baru')
                        <hr class="my-2">
                        <p><strong>Catatan:</strong> {{ $history->notes }}</p>
                    @endif
                </div>
            </div>
        </li>
    @empty
        <li>
            <div class="timeline-panel">
                <div class="timeline-body">
                    <p class="text-muted">Tidak ada riwayat untuk aset ini.</p>
                </div>
            </div>
        </li>
    @endforelse
</ul>

<style>
    .timeline { list-style: none; padding: 0; margin: 0; position: relative; }
    .timeline:before { content: ''; position: absolute; top: 5px; bottom: 5px; left: 15px; width: 3px; background-color: #e3e6f0; }
    .timeline > li { margin-bottom: 1.5rem; position: relative; padding-left: 45px; }
    .timeline > li .timeline-badge { color: #fff; width: 32px; height: 32px; line-height: 33px; font-size: 14px; text-align: center; position: absolute; top: 0; left: 0; background-color: #4e73df; z-index: 1; border-radius: 50%; }
    .timeline > li .timeline-panel { position: relative; padding: 1rem; border: 1px solid #e3e6f0; border-radius: .35rem; background-color: #fff; }
    .timeline > li .timeline-panel:before { content: " "; display: inline-block; position: absolute; top: 8px; left: -11px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-right: 8px solid #e3e6f0; }
    .timeline > li .timeline-panel:after { content: " "; display: inline-block; position: absolute; top: 9px; left: -10px; border-top: 7px solid transparent; border-bottom: 7px solid transparent; border-right: 7px solid #fff; }
    .timeline-title { margin-top: 0; color: #5a5c69; font-weight: bold; }
    .timeline-body > p, .timeline-body > ul { margin-bottom: 0; }
</style>