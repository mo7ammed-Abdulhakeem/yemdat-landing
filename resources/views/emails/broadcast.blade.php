<!DOCTYPE html>
<html lang="{{ $language ?? 'en' }}" dir="{{ ($language ?? 'en') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        body { margin: 0; padding: 0; background-color: #FAF8F3; font-family: Arial, Helvetica, sans-serif; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background-color: #593E2D; padding: 24px 32px; text-align: center; }
        .header img { height: 36px; }
        .header-text { color: #F2CB57; font-size: 20px; font-weight: bold; letter-spacing: 1px; }
        .content { padding: 32px; color: #3E2B20; font-size: 15px; line-height: 1.7; }
        .content h1, .content h2, .content h3 { color: #593E2D; }
        .content a { color: #C88D16; }
        .content ul, .content ol { padding-left: 24px; }
        .footer { background-color: #F5F0E8; padding: 20px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-text">YEMDAT</div>
        </div>
        <div class="content">
            {!! $body !!}
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Yemdat. All rights reserved.
        </div>
    </div>
</body>
</html>
