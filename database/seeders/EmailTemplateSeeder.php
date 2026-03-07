<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'mailable_class' => 'SignupOtpEmail',
                'subject_en' => 'Verify Your Email Address - Yemdat',
                'subject_ar' => 'تأكيد بريدك الإلكتروني - يمدات',
                'body_en' => 'Hi {name},<br><br>Thank you for signing up to Yemdat. Please use the following 6-digit OTP code to verify your email address:<br><br><strong>{otp}</strong><br><br>This code will expire in 10 minutes.',
                'body_ar' => 'مرحباً {name}،<br><br>شكراً لتسجيلك في يمدات. يرجى استخدام رمز التحقق المكون من 6 أرقام لتأكيد بريدك الإلكتروني:<br><br><strong>{otp}</strong><br><br>هذا الرمز صالح لمدة 10 دقائق.',
            ],
            [
                'mailable_class' => 'WelcomeEmail',
                'subject_en' => 'Welcome to the Yemdat Community!',
                'subject_ar' => 'أهلاً بك في مجتمع يمدات!',
                'body_en' => 'Hi {name},<br><br>Welcome to Yemdat! Your registration is now complete. You can now access your profile, register for events, and stay up-to-date with our latest news.<br><br>Thank you for joining us.',
                'body_ar' => 'أهلاً {name}،<br><br>مرحباً بك في يمدات! تم اكتمال تسجيلك بنجاح. يمكنك الآن الاطلاع على ملفك الشخصي والتسجيل في الفعاليات ومتابعة آخر أخبارنا.<br><br>شكراً لانضمامك إلينا.',
            ],
            [
                'mailable_class' => 'PasswordResetOtpEmail',
                'subject_en' => 'Password Reset Request - Yemdat',
                'subject_ar' => 'طلب استعادة كلمة المرور - يمدات',
                'body_en' => 'Hi {name},<br><br>We received a request to reset your password. Use the following OTP code to securely set a new password:<br><br><strong>{otp}</strong><br><br>If you did not request this, please ignore this email.',
                'body_ar' => 'مرحباً {name}،<br><br>تلقينا طلباً لاستعادة كلمة المرور الخاصة بك. استخدم رمز التحقق التالي لتعيين كلمة مرور جديدة بأمان:<br><br><strong>{otp}</strong><br><br>إذا لم تقم بطلب هذا، يرجى تجاهل هذا البريد.',
            ],
            [
                'mailable_class' => 'EventConfirmationEmail',
                'subject_en' => 'Registration Confirmed: {event_title}',
                'subject_ar' => 'تأكيد التسجيل في فعالية: {event_title}',
                'body_en' => 'Hi {name},<br><br>You have successfully registered for the event: <strong>{event_title}</strong>.<br><br><strong>Date/Time:</strong> {start_date}<br><strong>Location:</strong> {location}<br><br>{join_url_text}',
                'body_ar' => 'مرحباً {name}،<br><br>تم تسجيلك بنجاح في فعالية: <strong>{event_title}</strong>.<br><br><strong>الزمان:</strong> {start_date}<br><strong>المكان:</strong> {location}<br><br>{join_url_text}',
            ],
            [
                'mailable_class' => 'EventReminderEmail',
                'subject_en' => 'Reminder: {event_title} is tomorrow!',
                'subject_ar' => 'تذكير: فعالية {event_title} غداً!',
                'body_en' => 'Hi {name},<br><br>This is a quick reminder that the event <strong>{event_title}</strong> starts tomorrow.<br><br><strong>Date/Time:</strong> {start_date}<br><strong>Location:</strong> {location}<br><br>{join_url_text}<br><br>We look forward to seeing you there!',
                'body_ar' => 'مرحباً {name}،<br><br>هذا تذكير سريع بأن فعالية <strong>{event_title}</strong> ستقام غداً.<br><br><strong>الزمان:</strong> {start_date}<br><strong>المكان:</strong> {location}<br><br>{join_url_text}<br><br>نتطلع لرؤيتكم!',
            ],
            [
                'mailable_class' => 'ContactUsAutoReplyEmail',
                'subject_en' => 'We have received your message',
                'subject_ar' => 'لقد استلمنا رسالتك',
                'body_en' => 'Hi {name},<br><br>Thank you for getting in touch with us. We have received your message and our team will get back to you as soon as possible.<br><br>Best regards,<br>Yemdat Team',
                'body_ar' => 'مرحباً {name}،<br><br>شكراً لتواصلك معنا. لقد استلمنا رسالتك وسيقوم فريقنا بالرد عليك في أقرب وقت ممكن.<br><br>أطيب التحيات،<br>فريق يمدات',
            ],
            [
                'mailable_class' => 'TrainerAutoReplyEmail',
                'subject_en' => 'Thank you for your interest in becoming a Trainer - Yemdat',
                'subject_ar' => 'شكراً لاهتمامك بالانضمام كمدرب - يمدات',
                'body_en' => 'Hi {name},<br><br>We have successfully received your application to become a Trainer at <strong>Yemdat</strong>.<br><br>Our administrative team is currently reviewing your submission regarding <em>"{help_topic}"</em>.<br><br>We appreciate the time you took to share your expertise with us, and we will be in touch soon at <strong>{email}</strong> or <strong>{phone_number}</strong>.<br><br>Best regards,<br>Yemdat Team',
                'body_ar' => 'مرحباً {name}،<br><br>لقد استلمنا بنجاح طلبك للانضمام كمدرب في <strong>يمدات</strong>.<br><br>يقوم فريقنا الإداري حالياً بمراجعة طلبك بخصوص <em>"{help_topic}"</em>.<br><br>نقدر وقتك الذي أمضيته لمشاركة خبراتك معنا، وسنتواصل معك قريباً عبر <strong>{email}</strong> أو <strong>{phone_number}</strong>.<br><br>أطيب التحيات،<br>فريق يمدات',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::firstOrCreate(
            ['mailable_class' => $template['mailable_class']],
                $template
            );
        }
    }
}
