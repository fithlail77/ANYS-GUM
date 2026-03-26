<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\AssetService;
use App\Http\Resources\Api\AssetResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function index()
    {
        $assets = Asset::latest()->paginate(10);
        return AssetResource::collection($assets);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_name' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'current_owner' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $asset = $this->assetService->createAsset($request->all());
        
        return response()->json([
            'message' => 'Aset berhasil dibuat',
            'data' => new AssetResource($asset)
        ], 201);
    }

    public function show(Asset $asset)
    {
        // Eager load histories agar Flutter mendapatkan data riwayat
        $asset->load('histories');
        return new AssetResource($asset);
    }

    public function transfer(Request $request, Asset $asset)
    {
        $validator = Validator::make($request->all(), [
            'new_owner' => 'required|string|max:255',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updatedAsset = $this->assetService->transferAsset($asset, $request->all());

        return response()->json([
            'message' => 'Pindah tangan berhasil',
            'data' => new AssetResource($updatedAsset)
        ]);
    }
}