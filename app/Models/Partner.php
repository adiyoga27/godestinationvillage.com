<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Partner extends Model
{
    use LogsActivity;

    public $table = 'partners';

    protected static $logName = 'partners';

    public $fillable = [
        'name',
        'name_id',
        'desc',
        'desc_id',
        'logo',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'name_id', 'desc', 'desc_id', 'logo', 'url', 'sort_order', 'is_active']);
    }

    /**
     * URL logo: path asset bawaan (assets/...) atau file di storage/partners/.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        if (Str::startsWith($this->logo, ['http://', 'https://', 'assets/', 'storage/'])) {
            return asset($this->logo);
        }

        return asset('storage/partners/'.$this->logo);
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'id' ? ($this->name_id ?: $this->name) : $this->name;
    }

    public function localizedDesc(): ?string
    {
        return app()->getLocale() === 'id' ? ($this->desc_id ?: $this->desc) : $this->desc;
    }
}
