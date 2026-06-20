<?php

namespace App\Models\Concerns;

use App\Models\EmailReply;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Shared behaviour for records an admin can reply to from the panel
 * (Contact messages, TrainerRequest applications). Provides the reply
 * history relation, a workflow status, and small display helpers.
 *
 * The host model must have a string `status` column (default 'new').
 */
trait HasEmailReplies
{
    public const STATUS_NEW = 'new';

    public const STATUS_REPLIED = 'replied';

    public const STATUS_CLOSED = 'closed';

    /** @return array<string, string> value => label */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => __('global.status_new'),
            self::STATUS_REPLIED => __('global.status_replied'),
            self::STATUS_CLOSED => __('global.status_closed'),
        ];
    }

    public function replies(): MorphMany
    {
        return $this->morphMany(EmailReply::class, 'replyable')->latest();
    }

    /**
     * Mark the record as replied — unless it was manually 'closed', in which
     * case sending another reply shouldn't quietly reopen it.
     */
    public function markReplied(): void
    {
        if ($this->status !== self::STATUS_CLOSED) {
            $this->update(['status' => self::STATUS_REPLIED]);
        }
    }

    public function statusLabel(): string
    {
        return static::statusOptions()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_REPLIED => 'success',
            self::STATUS_CLOSED => 'gray',
            default => 'warning',
        };
    }
}
