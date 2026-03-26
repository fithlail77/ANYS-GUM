<ul class="nav nav-tabs" id="editAssetTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab"><i class="fa fa-info-circle"></i> Update Informasi Dasar</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="transfer-tab" data-toggle="tab" href="#transfer" role="tab"><i class="fa fa-exchange-alt"></i> Pindah Tangan (Transfer)</a>
    </li>
</ul>

<div class="tab-content p-3 border border-top-0 rounded-bottom" id="editAssetTabContent">
    {{-- Tab 1: Update Informasi Dasar --}}
    <div class="tab-pane fade show active" id="info" role="tabpanel">
        <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row gx-3 mb-3">
                <div class="col-md-6">
                    <label class="small mb-1">Nama Asset</label>
                    <input class="form-control" name="asset_name" type="text" value="{{ $asset->asset_name }}" required/>
                </div>
                <div class="col-md-6">
                    <label class="small mb-1">Nomor SAP Asset</label>
                    <input class="form-control" name="asset_sap_code" type="number" value="{{ $asset->asset_sap_code }}" required/>
                </div>
            </div>
            <div class="row gx-3 mb-3">
                <div class="col-md-6">
                    <label class="small mb-1">Kategori</label>
                    <select class="form-control" name="category" required>
                        <option value="Laptop" {{ $asset->category == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                        <option value="PC Desktop" {{ $asset->category == 'PC Desktop' ? 'selected' : '' }}>PC Desktop</option>
                        <option value="Elektronik" {{ $asset->category == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                        {{-- Tambahkan opsi lain sesuai kebutuhan --}}
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="small mb-1">Lokasi</label>
                    <input class="form-control" name="location" type="text" value="{{ $asset->location }}" required/>
                </div>
            </div>
            <div class="row gx-3 mb-3">
                <div class="col-md-6">
                    <label class="small mb-1">Pemilik Aset (Departemen)</label>
                    <input class="form-control" name="asset_owner" type="text" value="{{ $asset->asset_owner }}" required/>
                </div>
                <div class="col-md-6">
                    <label class="small mb-1">Kondisi</label>
                    <select class="form-control" name="condition" required>
                        <option value="Baik" {{ $asset->condition == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak" {{ $asset->condition == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
            </div>
            <div class="row gx-3 mb-3">
                <div class="col-md-6">
                    <label class="small mb-1">Tanggal Perolehan</label>
                    <input class="form-control" name="acquisition_date" type="date" value="{{ $asset->acquisition_date }}"/>
                </div>
                <div class="col-md-6">
                    <label class="small mb-1">Foto Baru (Opsional)</label>
                    <input type="file" name="photo" accept="image/*" class="form-control-file"/>
                </div>
            </div>
            <div class="mb-3">
                <label class="small mb-1">Deskripsi/Spesifikasi</label>
                <textarea class="form-control" name="description" rows="2">{{ $asset->description }}</textarea>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>

    {{-- Tab 2: Pindah Tangan (Transfer) --}}
    <div class="tab-pane fade" id="transfer" role="tabpanel">
        <div class="alert alert-info py-2">
            <i class="fa fa-info-circle"></i> Gunakan form ini jika aset berpindah tangan ke pengguna baru. Riwayat akan otomatis tercatat.
        </div>
        <form action="{{ route('assets.transfer', $asset->id) }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="small mb-1">Pengguna Saat Ini</label>
                    <input class="form-control bg-light" type="text" value="{{ $asset->current_owner }}" readonly/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="small mb-1">Pengguna Baru (Target)</label>
                    <input class="form-control border-primary" name="new_owner" type="text" placeholder="Masukkan nama pengguna baru" required/>
                </div>
                <div class="col-md-6">
                    <label class="small mb-1">Tanggal Pindah Tangan</label>
                    <input class="form-control border-primary" name="transfer_date" type="date" value="{{ date('Y-m-d') }}" required/>
                </div>
            </div>
            <div class="mb-3">
                <label class="small mb-1">Catatan Pindah Tangan</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Contoh: Serah terima jabatan, atau penggantian unit"></textarea>
            </div>
            <div class="text-right mt-4">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-exchange-alt"></i> Proses Pindah Tangan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .nav-tabs .nav-link.active {
        font-weight: bold;
        color: #4e73df;
        border-bottom: 3px solid #4e73df;
    }
    .tab-content {
        background-color: #f8f9fc;
    }
</style>