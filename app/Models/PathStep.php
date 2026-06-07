<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PathStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_path_id',
        'sort_order',
        'phase_en', 'phase_ar',
        'event_id',
        'title_en', 'title_ar',
        'url',
        'resource_type',
        'notes_en', 'notes_ar',
        'is_optional',
    ];

    protected $casts = [
        'is_optional' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Localized accessors. Internal (event) steps fall back to the linked
     * event's localized values so the view can treat every step uniformly.
     */
    public function getPhaseAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->phase_ar : $this->phase_en;
    }

    public function getTitleAttribute(): ?string
    {
        if ($this->isInternal() && $this->event) {
            return $this->event->title;
        }

        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getNotesAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->notes_ar : $this->notes_en;
    }

    /**
     * A step is internal when it is typed as an event AND links to one. Checking
     * resource_type too means a step switched to an external type still renders
     * externally even if a stale event_id lingers from before the change.
     */
    public function isInternal(): bool
    {
        return $this->resource_type === 'event' && $this->event_id !== null;
    }

    public function path(): BelongsTo
    {
        return $this->belongsTo(LearningPath::class, 'learning_path_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
