<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * One admin reply sent in response to a Contact message or a TrainerRequest.
 * Stored for audit / thread history; the email itself is sent via AdminReplyEmail.
 */
class EmailReply extends Model
{
    protected $fillable = [
        'from_name',
        'from_email',
        'subject',
        'body',
        'sent_by',
    ];

    public function replyable(): MorphTo
    {
        return $this->morphTo();
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
