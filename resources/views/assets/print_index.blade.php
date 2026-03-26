@extends('layouts.admin')

@section('title', 'Cetak Barcode & QR Code')

@section('content')
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
                            <th>Nama Asset</th>
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
                            <td>{{ $asset->asset_name }}</td>
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
        $('#printAssetsTable').DataTable({
            "paging": false,
            "info": true
        });

        $('#selectAll').on('click', function() {
            $('.asset-checkbox').prop('checked', this.checked);
        });
    });
</script>
@endpush