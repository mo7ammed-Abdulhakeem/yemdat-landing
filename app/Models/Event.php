<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title_en', 'title_ar',
        'slug',
        'format',
        'level',
        'specialty',
        'description_en', 'description_ar',
        'outcomes_en', 'outcomes_ar',
        'prerequisites_en', 'prerequisites_ar',
        'start_date',
        'end_date',
        'location',
        'image',
        'lecturer_name_en', 'lecturer_name_ar',
        'lecturer_title_en', 'lecturer_title_ar',
        'lecturer_image',
        'lecturer_linkedin',
        'join_url',
        'is_active',
        'created_by',
        'trainer_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class , 'created_by');
    }

    /**
     * The trainer (staff User, role=trainer) assigned to teach this event.
     */
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    /**
     * The specialty taxonomy entry this event is tagged with (if any).
     */
    public function specialtyOption()
    {
        return $this->belongsTo(Specialty::class, 'specialty', 'slug');
    }

    /**
     * Published learning paths this event is a step of (for the detail page).
     */
    public function learningPaths()
    {
        return $this->belongsToMany(LearningPath::class, 'path_steps', 'event_id', 'learning_path_id')
            ->where('learning_paths.is_published', true)
            ->distinct();
    }

    /**
     * Get the dynamic "what you'll learn" outcomes based on locale.
     */
    public function getOutcomesAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->outcomes_ar : $this->outcomes_en;
    }

    /**
     * Get the dynamic prerequisites based on locale.
     */
    public function getPrerequisitesAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->prerequisites_ar : $this->prerequisites_en;
    }

    /**
     * Get the dynamic title based on locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get the dynamic description based on locale
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Get the dynamic lecturer name based on locale
     */
    public function getLecturerNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->lecturer_name_ar : $this->lecturer_name_en;
    }

    /**
     * Get the dynamic lecturer title based on locale
     */
    public function getLecturerTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->lecturer_title_ar : $this->lecturer_title_en;
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the event's status (Upcoming, Ongoing, Past)
     */
    public function getStatusAttribute()
    {
        $now = Carbon::now();

        if ($this->end_date && $now->gt($this->end_date)) {
            return 'Past';
        }

        if ($now->gt($this->start_date) && (!$this->end_date || $now->lt($this->end_date))) {
            return 'Ongoing';
        }

        if ($now->gt($this->start_date)) {
            return 'Past'; // No end date provided, assuming single moment event passed
        }

        return 'Upcoming';
    }

    /**
     * Get days left until the event starts
     */
    public function getDaysLeftAttribute()
    {
        if ($this->start_date->isPast()) {
            return 0;
        }

        return $this->start_date->diffInDays(Carbon::now()) * -1; // Positive number of days
    }

    /**
     * Get a friendly remaining time string (e.g., "5 Days", "Today", "Passed")
     */
    public function getRemainingTimeAttribute()
    {
        $now = Carbon::now();

        if ($this->start_date->isPast()) {
            if ($this->end_date && $now->lt($this->end_date)) {
                return app()->getLocale() == 'ar' ? 'يحدث الآن' : 'Happening Now';
            }
            return app()->getLocale() == 'ar' ? 'انتهى' : 'Passed';
        }

        if ($this->start_date->isToday()) {
            return app()->getLocale() == 'ar' ? 'اليوم' : 'Today';
        }

        if ($this->start_date->isTomorrow()) {
            return app()->getLocale() == 'ar' ? 'غداً' : 'Tomorrow';
        }

        $days = $now->copy()->startOfDay()->diffInDays($this->start_date->copy()->startOfDay());

        if (app()->getLocale() == 'ar') {
            return $days == 2 ? 'يومين متبقيين' : $days . ' أيام متبقية';
        }

        return $days . ' Days Left';
    }

    /**
     * The members that are registered for the event.
     */
    public function members()
    {
        return $this->belongsToMany(Member::class , 'event_member', 'event_id', 'member_id')
            ->withPivot('attended_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Certificates issued for this event.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
