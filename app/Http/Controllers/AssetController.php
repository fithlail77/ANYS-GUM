<?php

namespace App\Http\Controllers;

use App\Exports\AssetsExport;
use App\Models\Asset;
use App\Services\AssetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{

    protected $assetService;

    // Inject Service melalui Constructor
    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('assets.index');
    }

    public function data(Request $request)
    {
        $query = Asset::select([
            'id',
            'asset_number',
            'asset_sap_code',
            'serial_number',
            'asset_name',
            'description',
            'category',
            'asset_owner',
            'current_owner',
            'location',
            'acquisition_date',
            'acquisition_cost',
            'depreciation',
            'condition',
            'photo_path'
        ]);

        // Filter: Hanya menampilkan asset yang pemiliknya (asset_owner) sesuai departemen user
        if (auth()->user()->department) {
            $query->where('asset_owner', auth()->user()->department);
        }

        $query->orderBy('asset_number', 'asc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_formatted', function ($row) {
                return Carbon::parse($row['acquisition_date'])->format('d-m-Y');
            })
            ->addColumn('aksi', function ($row) {
                return '<a href="#" class="btn btn-primary btn-sm detail-btn" title="Detail" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#modal-ShowDataAsset"><i class="fa fa-eye"></i></a> | 
                <a href="#" class="btn btn-success btn-sm edit-btn" title="Ubah Data" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#modal-EditAssets"><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'asset_sap_code' => 'required|integer',
            'serial_number' => 'nullable|string|max:255',
            'asset_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'asset_owner' => 'required|string|max:255',
            'current_owner' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'acquisition_cost' => 'nullable|numeric',
            'depreciation' => 'nullable|numeric',
            'condition' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $this->assetService->createAsset($validateData);

        return redirect()->route('assets.index')->with('success', 'Asset berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asset = Asset::with(['histories' => function ($query) {
            $query->orderBy('transfer_date', 'desc')->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        // Mengembalikan view partial untuk modal
        return view('assets._detail', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asset = Asset::findOrFail($id);
        return view('assets._edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asset = Asset::findOrFail($id);
        $validateData = $request->validate([
            'asset_sap_code' => 'required|integer',
            'serial_number' => 'nullable|string|max:255',
            'asset_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'asset_owner' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'acquisition_cost' => 'nullable|numeric',
            'depreciation' => 'nullable|numeric',
            'condition' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $this->assetService->updateAsset($asset, $validateData);

        return redirect()->back()->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * Proses Pindah Tangan Aset
     */
    public function transfer(Request $request, string $id)
    {
        $asset = Asset::findOrFail($id);
        $validateData = $request->validate([
            'new_owner' => 'required|string|max:255',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $this->assetService->transferAsset($asset, $validateData);

        return redirect()->back()->with('success', 'Aset berhasil dipindahtangankan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function printIndex()
    {
        // Mengambil semua data aset untuk ditampilkan di tabel pemilihan
        $query = \App\Models\Asset::query();

        // Filter: Hanya menampilkan asset yang sesuai departemen user untuk halaman print
        if (auth()->user()->department) {
            $query->where('asset_owner', auth()->user()->department);
        }

        $assets = $query->get();
        return view('assets.print_index', compact('assets'));
    }

    public function printProcess(Request $request)
    {
        $ids = $request->input('asset_ids', []);
        $type = $request->input('print_type', 'barcode');

        if (empty($ids)) {
            return back()->with('error', 'Silakan pilih minimal satu aset.');
        }

        $assets = \App\Models\Asset::whereIn('id', $ids)->get();
        return view('assets.print_labels', compact('assets', 'type'));
    }

    /**
     * Fungsi untuk ekspor data ke Excel
     */
    public function export()
    {
        return Excel::download(new AssetsExport, 'data_asset_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Mencari ID aset berdasarkan asset_number atau asset_sap_code
     */
    public function findByCode($code)
    {
        $query = Asset::query();

        $query->where(function($q) use ($code) {
            $q->where('asset_number', $code);
            // Hanya cari di asset_sap_code dan serial_number jika input numerik untuk mencegah error di PostgreSQL
            if (is_numeric($code)) {
                $q->orWhere('asset_sap_code', $code);
            }
            $q->orWhere('serial_number', 'like', '%' . $code . '%');
        });

        if (auth()->user()->department) {
            $query->where('asset_owner', auth()->user()->department);
        }

        $asset = $query->first();

        if ($asset) {
            return response()->json(['success' => true, 'id' => $asset->id]);
        }

        return response()->json(['success' => false, 'message' => 'Asset tidak ditemukan.']);
    }
}
