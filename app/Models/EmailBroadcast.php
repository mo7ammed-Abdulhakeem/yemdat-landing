<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailBroadcast extends Model
{
    protected $fillable = [
        'subject_en', 'subject_ar',
        'body_en', 'body_ar',
        'audience_type', 'event_id', 'language',
        'from_email', 'from_name',
        'sent_by', 'status', 'total_recipients', 'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function recipients(): HasMany
    {
        return $this->hasMany(EmailBroadcastRecipient::class, 'broadcast_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function getOpensCountAttribute(): int
    {
        return $this->recipients()->where('open_count', '>', 0)->count();
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->total_recipients === 0) {
            return 0;
        }
        return round(($this->opens_count / $this->total_recipients) * 100, 1);
    }

    public function getUnsubscribesCountAttribute(): int
    {
        return $this->recipients()->whereNotNull('unsubscribed_at')->count();
    }
}
