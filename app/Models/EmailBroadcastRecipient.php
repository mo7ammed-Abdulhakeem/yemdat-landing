<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailBroadcastRecipient extends Model
{
    protected $fillable = [
        'broadcast_id', 'member_id', 'email',
        'tracking_token', 'opened_at', 'open_count', 'unsubscribed_at',
    ];

    protected $casts = [
        'opened_at'       => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function broadcast(): BelongsTo
    {
        return $this->belongsTo(EmailBroadcast::class, 'broadcast_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
