<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;

class Asset extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded() // Mencatat semua field karena menggunakan $guarded bukan $fillable
            ->logOnlyDirty() // Hanya mencatat data yang berubah saja
            ->dontSubmitEmptyLogs(); // Jangan simpan log jika tidak ada perubahan
    }

    /**
     * Menambahkan informasi tambahan ke dalam log properties
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('asset_info', [
            'name' => $this->asset_name,
            'number' => $this->asset_number,
        ]);
    }
}
