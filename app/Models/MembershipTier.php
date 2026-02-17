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
     */
    public function getFeaturesAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->features_ar : $this->features_en;
    }
}
