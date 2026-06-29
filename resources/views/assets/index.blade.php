@extends('layouts.admin')

@section('title', 'Data Asset')

@section('content')
<hr>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <div>
            <button class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#modal-AddDataAsset" align="right">
                <i class="fa fa-plus"></i> Tambah Asset
            </button>
        </div>
        <div>
            <a href="{{ route('assets.export') }}" class="btn btn-success btn-sm btn-flat">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <table id="DataAssetTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Asset</th>
                    <th>No SAP</th>
                    <th>Nama Asset</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Pengguna</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <div id="noDataMessage" class="alert alert-warning mt-3" style="display:none;">
                Tidak ada data.
            </div>
        </table>
    </div>
</div>
<!-- Modal Tambah Data Asset -->
<div class="modal fade" id="modal-AddDataAsset" tabindex="-1" role="dialog" aria-labelledby="modal-AddDataAssetLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-AddDataAssetLabel">Tambah Data Asset</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="row gx-3 mb-3">
                    <div class="col-md-3">
                        <label class="small mb-1">Nomor SAP Asset</label>
                        <input class="form-control" name="asset_sap_code" type="number"/>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Serial Number</label>
                        <input class="form-control" name="serial_number" type="text"/>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Nama Asset</label>
                        <input class="form-control" name="asset_name" type="text"/>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Kategori Aset</label>
                        <select class="form-control" name="category" required>
                            <option value="">-- Pilih --</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Kenderaan">Kenderaan</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Laptop">Laptop</option>
                            <option value="PC Desktop">PC Desktop</option>
                            <option value="Monitor">Monitor</option>
                            <option value="Printer">Printer</option>
                            <option value="Switch">Switch</option>
                            <option value="Router">Router</option>
                            <option value="AP Indoor">AP Indoor</option>
                            <option value="AP Outdoor">AP Outdoor</option>
                            <option value="HT">HT</option>
                            <option value="Rig">Rig</option>
                            <option value="Proyektor">Proyektor</option>
                            <option value="Peralatan DAMKAR">Peralatan DAMKAR</option>
                            <option value="Peralatan Lingkungan">Peralatan Lingkungan</option>
                            <option value="Peralatan Safety">Peralatan Safety</option>
                            <option value="Peralatan HCV">Peralatan HCV</option>
                        </select>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-3">
                        <label class="small mb-1">Pemilik Asset</label>
                        <select class="form-control" name="asset_owner" required>
                            <option value="">-- Pilih --</option>
                            <option value="IT">IT</option>
                            <option value="Umum">Umum</option>
                            <option value="EHSS">EHSS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Pengguna Asset</label>
                        <input class="form-control" name="current_owner" type="text"/>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Lokasi Asset</label>
                        <input class="form-control" name="location" type="text"/>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Tanggal Perolehan Asset</label>
                        <input class="form-control" name="acquisition_date" type="date" />
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-3">
                        <label class="small mb-1">Biaya Perolehan Asset</label>
                        <input class="form-control" name="acquisition_cost" type="number"/>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Depresiasi Asset</label>
                        <input class="form-control" name="depreciation" type="number"/>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Kondisi Asset</label>
                       <select class="form-control" name="condition" required>
                            <option value="">-- Pilih --</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Disposal">Disposal</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Foto Barang (Opsional)</label>
                        <input type="file" name="photo" accept="image/*"/>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-3">
                        <label class="small mb-1">Deskripsi/Spesifikasi Aset</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Detail Data Asset -->
<div class="modal fade" id="modal-ShowDataAsset" tabindex="-1" role="dialog" aria-labelledby="modal-ShowDataAssetLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-ShowDataAssetLabel">Detail Data Asset</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Data Asset -->
<div class="modal fade" id="modal-EditAssets" tabindex="-1" role="dialog" aria-labelledby="modal-EditAssetsLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-EditAssetsLabel">Update Data & Riwayat Asset</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <!-- Konten diisi via AJAX -->
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    var table = $('#DataAssetTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        responsive: false,
        autoWidth: false,
        ajax: {
            url: "{{ route('assets.data') }}",
            data: function(d) {
                d.minDate = $('#minDate').val();
                d.maxDate = $('#maxDate').val();
            }
        },
        drawCallback: function(settings) {
            var api = this.api();
            var dataCount = api.data().count();
            if (dataCount === 0) {
                $('#noDataMessage').show();
            } else {
                $('#noDataMessage').hide();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'asset_number', name: 'asset_number' },
            { data: 'asset_sap_code', name: 'asset_sap_code' },
            { data: 'asset_name', name: 'asset_name' },
            { data: 'category', name: 'category' },
            { data: 'location', name: 'location' },
            { data: 'current_owner', name: 'current_owner' },
            { data: 'condition', name: 'condition' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ]
    });

    // Handle klik tombol detail
    $('#DataAssetTable').on('click', '.detail-btn', function(e) {
        e.preventDefault();

        var assetId = $(this).data('id');
        var url = "{{ route('assets.show', ':id') }}".replace(':id', assetId);
        var modal = $('#modal-ShowDataAsset');
        var modalBody = modal.find('.modal-body');

        // Tampilkan loading spinner
        modalBody.html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x"></i><p class="mt-2">Memuat data...</p></div>');

        // Lakukan request AJAX
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                modalBody.html(response);
            },
            error: function(xhr) {
                modalBody.html('<div class="alert alert-danger">Gagal memuat data. Silakan coba lagi.</div>');
            }
        });
    });

    // Handle klik tombol edit
    $('#DataAssetTable').on('click', '.edit-btn', function(e) {
        e.preventDefault();

        var assetId = $(this).data('id');
        var url = "{{ route('assets.edit', ':id') }}".replace(':id', assetId);
        var modal = $('#modal-EditAssets');
        var modalBody = modal.find('.modal-body');

        modalBody.html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x"></i><p class="mt-2">Memuat Form...</p></div>');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                modalBody.html(response);
            },
            error: function(xhr) {
                modalBody.html('<div class="alert alert-danger">Gagal memuat form edit.</div>');
            }
        });
    });
});
</script>
@endpush