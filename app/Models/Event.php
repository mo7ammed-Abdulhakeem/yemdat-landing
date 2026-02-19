<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en', 'title_ar',
        'slug',
        'description_en', 'description_ar',
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
    ];

    public function creator()
    {
        return $this->belongsTo(User::class , 'created_by');
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
                return 'Happening Now';
            }
            return 'Passed';
        }

        if ($this->start_date->isToday()) {
            return 'Today';
        }

        $days = $now->diffInDays($this->start_date, false);
        $days = ceil($days); // Round up partial days

        if ($days <= 1) {
            return 'Tomorrow';
        }

        return intval($days) . ' Days Left';
    }
}
