<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\AssetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        ])->orderBy('asset_number', 'asc');

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
        $assets = \App\Models\Asset::all();
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
}
