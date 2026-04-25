<?php

namespace App\Http\Controllers;

use App\Models\EmailBroadcastRecipient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrackingController extends Controller
{
    // 1×1 transparent GIF (base64)
    private const PIXEL_GIF = 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    public function openPixel(string $token): Response
    {
        $recipient = EmailBroadcastRecipient::where('tracking_token', $token)->first();

        if ($recipient) {
            $recipient->increment('open_count');
            if (is_null($recipient->opened_at)) {
                $recipient->update(['opened_at' => now()]);
            }
        }

        return response(base64_decode(self::PIXEL_GIF), 200, [
            'Content-Type'  => 'image/gif',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    public function unsubscribePage(string $token)
    {
        $recipient = EmailBroadcastRecipient::where('tracking_token', $token)
            ->with('member', 'broadcast')
            ->firstOrFail();

        return view('unsubscribe', compact('recipient'));
    }

    public function unsubscribeConfirm(Request $request, string $token)
    {
        $recipient = EmailBroadcastRecipient::where('tracking_token', $token)
            ->with('member')
            ->firstOrFail();

        if (is_null($recipient->unsubscribed_at)) {
            $recipient->update(['unsubscribed_at' => now()]);

            if ($recipient->member) {
                $recipient->member->update(['unsubscribed_at' => now()]);
            }
        }

        return view('unsubscribe', ['recipient' => $recipient, 'confirmed' => true]);
    }

    public function resubscribeByToken(string $token)
    {
        $recipient = EmailBroadcastRecipient::where('tracking_token', $token)
            ->with('member')
            ->firstOrFail();

        if ($recipient->member) {
            $recipient->member->update(['unsubscribed_at' => null]);
        }

        return view('unsubscribe', ['recipient' => $recipient, 'resubscribed' => true]);
    }
}
