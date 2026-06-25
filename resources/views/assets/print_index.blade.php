@extends('layouts.admin')

@section('title', 'Cetak Barcode & QR Code')

@section('content')
<hr>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pilih Asset untuk Dicetak</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.print-process') }}" method="POST" target="_blank">
            @csrf
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <button type="submit" name="print_type" value="barcode" class="btn btn-primary mr-2">
                        <i class="fas fa-barcode"></i> Cetak Barcode
                    </button>
                    <button type="submit" name="print_type" value="qrcode" class="btn btn-success">
                        <i class="fas fa-qrcode"></i> Cetak QR Code
                    </button>
                </div>
                <span class="badge badge-info p-2">
                    <i class="fas fa-info-circle"></i> Format: Niimbot 50x30mm (HD-366)
                </span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered" id="printAssetsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50px">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Nomor Asset</th>
                            <th>SAP Code</th>
                            <th>Serial Number</th>
                            <th>Nama Asset</th>
                            <th>Pengguna</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $asset)
                        <tr>
                            <td>
                                <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}" class="asset-checkbox">
                            </td>
                            <td>{{ $asset->asset_number }}</td>
                            <td>{{ $asset->asset_sap_code }}</td>
                            <td>{{ $asset->serial_number ?? '-' }}</td>
                            <td>{{ $asset->asset_name }}</td>
                            <td>{{ $asset->current_owner }}</td>
                            <td>{{ $asset->category }}</td>
                            <td>{{ $asset->location }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#printAssetsTable').DataTable({
            "paging": true,
            "pageLength": 10,
            "info": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/English.json"
            }
        });

        // Handle "Pilih Semua" untuk seluruh halaman (bukan hanya yang terlihat)
        $('#selectAll').on('click', function() {
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input.asset-checkbox', rows).prop('checked', this.checked);
        });

        // Pastikan checkbox di halaman lain tetap terkirim saat form disubmit
        $('form').on('submit', function(e) {
            var form = this;
            table.$('input.asset-checkbox:checked').each(function() {
                if (!$.contains(document, this)) {
                    $(form).append(
                        $('<input>').attr('type', 'hidden').attr('name', 'asset_ids[]').val($(this).val())
                    );
                }
            });
        });
    });
</script>
@endpush