<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nomor_aset' => $this->asset_number,
            'nama' => $this->name,
            'pemilik_saat_ini' => $this->current_owner,
            'tanggal_perolehan' => $this->acquisition_date,
            'deskripsi' => $this->description,
            'foto_url' => $this->photo_path ? asset('storage/' . $this->photo_path) : null,
            'riwayat' => AssetHistoryResource::collection($this->whenLoaded('histories')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
