<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yemdat Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}; text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
    
    @if($banner)
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ filter_var($banner, FILTER_VALIDATE_URL) ? $banner : asset('storage/' . $banner) }}" alt="Yemdat Banner" style="max-width: 100%; height: auto; border-radius: 8px;">
        </div>
    @endif

    <div style="background-color: #f9fafb; padding: 25px; border-radius: 12px; border: 1px solid #f3f4f6;">
        {!! $body !!}
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 12px; color: #888;">
        &copy; {{ date('Y') }} Yemdat. {{ app()->getLocale() == 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
    </div>
</body>
</html>
