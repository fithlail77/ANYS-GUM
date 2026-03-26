<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    } 
}
