<?php

namespace App\Actions\Trainers;

use App\Models\Member;

/**
 * Revokes a member's trainer status: unlinks the member and deletes the linked
 * trainer User. Events assigned to that trainer have their trainer_id nulled by
 * the foreign key (nullOnDelete), so the events themselves are untouched.
 */
class RevokeTrainer
{
    public function execute(Member $member): void
    {
        $user = $member->user;

        $member->forceFill(['user_id' => null])->save();

        if ($user && $user->role === 'trainer') {
            $user->delete();
        }
    }
}
