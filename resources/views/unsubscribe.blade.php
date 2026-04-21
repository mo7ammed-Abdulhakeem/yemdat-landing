<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe — Yemdat</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #FAF8F3; font-family: Arial, Helvetica, sans-serif; display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 24px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.08); max-width: 420px; width: 100%; padding: 40px 32px; text-align: center; }
        .logo { color: #593E2D; font-size: 22px; font-weight: bold; letter-spacing: 1px; margin-bottom: 24px; }
        .logo span { color: #F2CB57; }
        h1 { font-size: 20px; color: #3E2B20; margin-bottom: 12px; }
        p { font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 20px; }
        .name { color: #593E2D; font-weight: bold; }
        .btn-unsub { background: #dc2626; color: #fff; border: none; border-radius: 8px; padding: 12px 28px; font-size: 15px; font-weight: bold; cursor: pointer; width: 100%; transition: background .2s; }
        .btn-unsub:hover { background: #b91c1c; }
        .success-icon { font-size: 48px; margin-bottom: 16px; }
        .already { color: #9ca3af; font-size: 13px; margin-top: 8px; }
        a.back { display: inline-block; margin-top: 20px; color: #593E2D; font-size: 13px; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">YEM<span>DAT</span></div>

        @if(isset($confirmed) && $confirmed)
            {{-- Success state --}}
            <div class="success-icon">✅</div>
            <h1>You've been unsubscribed</h1>
            <p>
                <span class="name">{{ $recipient->member->full_name ?? $recipient->email }}</span>,
                you will no longer receive general broadcast emails from Yemdat.
                <br><br>
                You will still receive important emails such as event confirmations and OTPs.
            </p>
            <a href="{{ url('/') }}" class="back">← Return to Yemdat</a>

        @elseif($recipient->unsubscribed_at)
            {{-- Already unsubscribed --}}
            <div class="success-icon">ℹ️</div>
            <h1>Already unsubscribed</h1>
            <p class="already">
                <span class="name">{{ $recipient->member->full_name ?? $recipient->email }}</span>,
                you are already unsubscribed from general emails.
            </p>
            <a href="{{ url('/') }}" class="back">← Return to Yemdat</a>

        @else
            {{-- Confirmation prompt --}}
            <h1>Unsubscribe from emails?</h1>
            <p>
                Hi <span class="name">{{ $recipient->member->full_name ?? $recipient->email }}</span>,<br><br>
                You are about to unsubscribe from general broadcast emails from Yemdat.
                You will still receive event confirmations and security emails.
            </p>
            <form action="{{ route('unsubscribe.confirm', $recipient->tracking_token) }}" method="POST">
                @csrf
                <button type="submit" class="btn-unsub">Yes, Unsubscribe Me</button>
            </form>
            <a href="{{ url('/') }}" class="back">No, keep me subscribed</a>
        @endif
    </div>
</body>
</html>
