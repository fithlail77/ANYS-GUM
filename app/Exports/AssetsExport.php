<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings; // Tambahkan ini
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetsExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithStyles
{
    private $rowNumber = 0;
    protected $assets; // Properti untuk menampung data

    /**
    * Mengambil data aset berdasarkan filter departemen user (sama seperti di Controller)
    */
    public function __construct()
    {
        // Ambil data langsung saat class diinisialisasi
        $query = Asset::query();

        if (auth()->check() && auth()->user()->department) {
            $query->where('asset_owner', auth()->user()->department);
        }

        $this->assets = $query->orderBy('asset_number', 'asc')->get();
    }

    public function collection()
    {
        // Mengembalikan data yang sudah diambil di constructor
        return $this->assets;
    }

    /**
    * Mendefinisikan Judul Kolom di Excel
    */
    public function headings(): array
    {
        return [
            'No', 'Nomor Asset', 'Nomor Asset SAP', 'Nama Asset', 'Deskripsi Aset', 'Kategori', 
            'Pemilik Aset', 'Pengguna Aset', 'Lokasi', 'Tanggal Perolehan Aset', 
            'Nilai Perolehan Aset', 'Depresiasi', 'Kondisi', 'Foto Aset'
        ];
    }

    /**
    * Memetakan data dari Model ke Kolom Excel
    */
    public function map($asset): array
    {
        return [
            ++$this->rowNumber,
            $asset->asset_number,
            $asset->asset_sap_code,
            $asset->asset_name,
            $asset->description,
            $asset->category,
            $asset->asset_owner,
            $asset->current_owner,
            $asset->location,
            $asset->acquisition_date,
            $asset->acquisition_cost,
            $asset->depreciation,
            $asset->condition,
            '',
        ];
    }

    /**
     * Menempatkan gambar ke dalam cell
     */
    public function drawings()
    {
        $drawings = [];
        foreach ($this->assets as $index => $asset) {
            if ($asset->photo_path && file_exists(storage_path('app/public/' . $asset->photo_path))) {
                $drawing = new Drawing();
                $drawing->setName($asset->asset_name);
                $drawing->setDescription($asset->description);
                $drawing->setPath(storage_path('app/public/' . $asset->photo_path));
                $drawing->setHeight(50); // Tinggi gambar dalam pixel
                
                // Koordinat kolom 'Foto Aset' adalah N (kolom ke-14)
                // Baris dimulai dari 2 (karena baris 1 adalah header)
                $column = 'N';
                $row = $index + 2; 
                $drawing->setCoordinates($column . $row);
                
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    /**
     * Mengatur tinggi baris agar gambar tidak tumpang tindih
     */
    public function styles(Worksheet $sheet)
    {
        // Set tinggi baris untuk semua data (dimulai dari baris 2)
        for ($i = 2; $i <= count($this->assets) + 1; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(45);
        }
        
        // Atur lebar kolom foto agar pas
        $sheet->getColumnDimension('N')->setWidth(15);
    }
}