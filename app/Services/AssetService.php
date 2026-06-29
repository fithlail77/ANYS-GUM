<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AssetService
{
    protected $companyCode = 'GUM'; // Singkatan Perusahaan

    /**
     * Membuat Aset Baru
     */
    public function createAsset(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Generate Nomor Aset
            $data['asset_number'] = $this->generateAssetNumber($data['acquisition_date']);

            // 1b. Handle Current Owner: Jika kosong, default ke Asset Owner (Departemen)
            if (empty($data['current_owner'])) {
                $data['current_owner'] = $data['asset_owner'];
            }

            // 2. Upload Foto jika ada
            if (isset($data['photo'])) {
                $data['photo_path'] = $data['photo']->store('assets/photos', 'public');
                unset($data['photo']); // Hapus instance file dari array data
            }

            // 3. Simpan Aset
            $asset = Asset::create($data);

            // 4. Catat History Pertama (Perolehan)
            AssetHistory::create([
                'asset_id' => $asset->id,
                'previous_owner' => '-', // Tambahkan baris ini sebagai nilai default
                'new_owner' => $asset->current_owner,
                'transfer_date' => $asset->acquisition_date,
                'notes' => 'Perolehan Aset Baru'
            ]);

            return $asset;
        });
    }

    /**
     * Generate Nomor Aset Otomatis (Format: YYYYMM-CMP-0001)
     */
    private function generateAssetNumber($date)
    {
        $yearMonth = Carbon::parse($date)->format('Ym');
        $prefix = "{$yearMonth}-{$this->companyCode}-";

        // Perbaikan: Ambil ID terakhir secara global agar nomor urut mengikuti ID (Running Number Global)
        // Jika tabel kosong, mulai dari 0. Jika ada ID 2, maka selanjutnya 3.
        $lastId = Asset::withoutGlobalScope('department')->max('id') ?? 0;
        $newNumber = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    /**
     * Proses Pindah Tangan Aset
     */
    public function transferAsset(Asset $asset, array $data)
    {
        return DB::transaction(function () use ($asset, $data) {
            $previousOwner = $asset->current_owner;

            // 1. Catat History
            AssetHistory::create([
                'asset_id' => $asset->id,
                'previous_owner' => $previousOwner,
                'new_owner' => $data['new_owner'],
                'transfer_date' => $data['transfer_date'],
                'notes' => $data['notes'] ?? 'Pindah Tangan'
            ]);

            // 2. Update Pemilik di Tabel Asset
            $asset->update(['current_owner' => $data['new_owner']]);

            return $asset;
        });
    }

    /**
     * Update Data Aset (Koreksi nama, deskripsi, atau foto)
     */
    public function updateAsset(Asset $asset, array $data)
    {
        // Jika ada upload foto baru
        if (isset($data['photo'])) {
            // Hapus foto lama jika ada
            if ($asset->photo_path && Storage::disk('public')->exists($asset->photo_path)) {
                Storage::disk('public')->delete($asset->photo_path);
            }
            // Simpan foto baru
            $data['photo_path'] = $data['photo']->store('assets/photos', 'public');
            unset($data['photo']);
        }

        $asset->update($data);
        return $asset;
    }

    /**
     * Hapus Data Aset beserta fotonya
     */
    public function deleteAsset(Asset $asset)
    {
        return DB::transaction(function () use ($asset) {
            // Hapus foto dari storage
            if ($asset->photo_path && Storage::disk('public')->exists($asset->photo_path)) {
                Storage::disk('public')->delete($asset->photo_path);
            }
            
            // Hapus data (History akan otomatis terhapus karena cascadeOnDelete di database)
            return $asset->delete();
        });
    }
}