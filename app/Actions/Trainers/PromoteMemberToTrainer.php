<?php

namespace App\Actions\Trainers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Promotes an existing community Member into a Trainer: creates/links a staff User
 * (role=trainer) and emails them a link to set their /trainer panel password.
 *
 * Idempotent-ish: if the member is already a trainer it just returns the linked user.
 * Aborts if the member's email already belongs to an admin/super-admin (never hijack
 * an existing admin account).
 */
class PromoteMemberToTrainer
{
    public function execute(Member $member): User
    {
        // Already a trainer — nothing to do.
        if ($member->user_id && $member->user && $member->user->role === 'trainer') {
            return $member->user;
        }

        $existing = User::where('email', $member->email)->first();

        if ($existing && in_array($existing->role, ['admin', 'super_admin'], true)) {
            throw new RuntimeException('An admin account already uses this email address.');
        }

        $user = $existing ?: new User([
            'name' => $member->full_name,
            'email' => $member->email,
            // Throwaway password; the trainer sets their own via the invite link.
            'password' => Str::random(40),
        ]);

        $user->role = 'trainer';
        $user->save();

        $member->forceFill(['user_id' => $user->id])->save();

        app(SendTrainerInvite::class)->execute($user, $member->full_name);

        return $user;
    }
}
