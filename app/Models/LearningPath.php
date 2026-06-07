<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_en', 'title_ar',
        'summary_en', 'summary_ar',
        'description_en', 'description_ar',
        'image',
        'level',
        'specialty',
        'estimated_weeks',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'estimated_weeks' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Localized accessors based on the current locale.
     */
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getSummaryAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->summary_ar : $this->summary_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Ordered steps that make up this path.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(PathStep::class)->orderBy('sort_order');
    }

    /**
     * The specialty taxonomy entry this path targets (if any).
     */
    public function specialtyOption(): BelongsTo
    {
        return $this->belongsTo(Specialty::class, 'specialty', 'slug');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title_en');
    }
}
