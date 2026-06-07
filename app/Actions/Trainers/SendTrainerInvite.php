<?php

namespace App\Actions\Trainers;

use App\Mail\TrainerInviteEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

/**
 * Emails a trainer a link to set their /trainer panel password. Used both when a
 * member is first promoted and when an admin re-sends the invite (e.g. the first
 * email failed or its link expired).
 */
class SendTrainerInvite
{
    public function execute(User $user, string $name): void
    {
        // The trainer panel's Filament password-reset route is signed, so we build a
        // temporary signed URL (same as Filament's own reset email) — a plain route()
        // URL is rejected with a 403.
        $token = Password::broker('users')->createToken($user);

        $setPasswordUrl = URL::temporarySignedRoute(
            'filament.trainer.auth.password-reset.reset',
            now()->addMinutes((int) config('auth.passwords.users.expire', 60)),
            ['email' => $user->email, 'token' => $token],
        );

        Mail::to($user->email)->send(new TrainerInviteEmail([
            'name' => $name,
            'set_password_url' => $setPasswordUrl,
            'login_url' => route('filament.trainer.auth.login'),
        ]));
    }
}
