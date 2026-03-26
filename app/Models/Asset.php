<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $guarded = ['id'];

    public function histories()
    {
        return $this->hasMany(AssetHistory::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('department', function ($builder) {
            if (auth()->check() && auth()->user()->department) {
                $builder->where('asset_owner', auth()->user()->department);
            }
        });
    }
}
