<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'features_en',
        'features_ar',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features_en' => 'array',
        'features_ar' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the name based on the current locale.
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get the description based on the current locale.
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Get the features based on the current locale.
     *
     * Always returns a clean, indexed array. Tolerates legacy/malformed data
     * (a JSON-encoded string or a comma-joined string instead of an array) so
     * the public page never 500s on `count()`.
     */
    public function getFeaturesAttribute(): array
    {
        $features = app()->getLocale() === 'ar' ? $this->features_ar : $this->features_en;

        if (is_string($features)) {
            $decoded = json_decode($features, true);
            $features = is_array($decoded)
                ? $decoded
                : array_map('trim', explode(',', $features));
        }

        if (! is_array($features)) {
            return [];
        }

        return array_values(array_filter($features, fn ($f) => filled($f)));
    }
}
