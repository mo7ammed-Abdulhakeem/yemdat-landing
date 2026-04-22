<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('email_templates')
            ->where('mailable_class', 'EventConfirmationEmail')
            ->update([
                'subject_en' => 'Registration Confirmed: {event_title}',
                'subject_ar' => 'تأكيد التسجيل في فعالية: {event_title}',
                'body_en'    => 'Hi {name},<br><br>You have successfully registered for the event: <strong>{event_title}</strong>.<br><br><strong>Date/Time:</strong> {start_date}<br><strong>Location:</strong> {location}<br><br>{join_url_text}',
                'body_ar'    => 'مرحباً {name}،<br><br>تم تسجيلك بنجاح في فعالية: <strong>{event_title}</strong>.<br><br><strong>الزمان:</strong> {start_date}<br><strong>المكان:</strong> {location}<br><br>{join_url_text}',
            ]);
    }

    public function down(): void
    {
        //
    }
};
