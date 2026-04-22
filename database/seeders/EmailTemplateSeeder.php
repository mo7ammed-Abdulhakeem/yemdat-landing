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
                'mailable_class' => 'AccountDeletionOtpEmail',
                'subject_en' => 'Account Deletion Verification Code - Yemdat',
                'subject_ar' => 'رمز التحقق لحذف الحساب - يمدات',
                'body_en' => 'Hi {name},<br><br>You requested to delete your Yemdat account. Please use the verification code below to confirm:<br><br><strong>{otp}</strong><br><br>This code expires in 15 minutes. If you did not request this, you can safely ignore this email.',
                'body_ar' => 'مرحباً {name}،<br><br>لقد طلبت حذف حسابك في يمدات. يرجى استخدام رمز التحقق أدناه للتأكيد:<br><br><strong>{otp}</strong><br><br>ينتهي صلاحية هذا الرمز خلال 15 دقيقة. إذا لم تقم بهذا الطلب، يمكنك تجاهل هذه الرسالة بأمان.',
            ],
            [
                'mailable_class' => 'TrainerAutoReplyEmail',
                'subject_en' => 'Thank you for your interest in becoming a Trainer - Yemdat',
                'subject_ar' => 'شكراً لاهتمامك بالانضمام كمدرب - يمدات',
                'body_en' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        .email-container {
            font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff;
        }
        .header {
            background-color: #fdfbf7;
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #c59d5f; /* Yemdat Gold */
        }
        .logo {
            max-width: 180px;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.8;
            color: #333333;
            text-align: left;
        }
        .highlight {
            color: #5c4033; /* Yemdat Dark Brown */
            font-weight: bold;
        }
        .topic-box {
            background-color: #f9f9f9;
            border-left: 5px solid #c59d5f;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }
        .footer {
            background-color: #5c4033;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .contact-info {
            color: #c59d5f;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        

        <div class="content">
            <p>Hi <span class="highlight">{name}</span>,</p>
            
            <p>We are thrilled to receive your application to join the elite group of trainers at <span class="highlight">Yemdat</span>. This email confirms that your submission has been successfully received.</p>
            
            <p>Our administrative and technical teams are currently reviewing your training proposal titled:</p>
            
            <div class="topic-box">
                "{help_topic}"
            </div>

            <p>We truly appreciate the time you took to share your expertise with our community. We will be in touch shortly to discuss the next steps via:</p>
            
            <p>📧 Email: <span class="contact-info">{email}</span><br>
               📞 Phone: <span class="contact-info">{phone_number}</span></p>
        </div>

        <div class="footer">
            <p>Thank you for being part of the digital transformation journey.</p>
            <strong>The Yemdat Team</strong>
        </div>
    </div>
</body>
</html>',
                'body_ar' => '<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        .email-container {
            font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff;
        }
        .header {
            background-color: #fdfbf7; /* لون خلفية فاتح من وحي الموقع */
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #c59d5f; /* اللون الذهبي من الشعار */
        }
        .logo {
            max-width: 180px;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.8;
            color: #333333;
            text-align: right;
        }
        .highlight {
            color: #5c4033; /* اللون البني من زر لوحة التحكم */
            font-weight: bold;
        }
        .topic-box {
            background-color: #f9f9f9;
            border-right: 5px solid #c59d5f;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }
        .footer {
            background-color: #5c4033; /* البني الداكن */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .contact-info {
            color: #c59d5f;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="https://yemdat.com/assets/images/logo.png" alt="Yemdat Logo" class="logo">
        </div>

        <div class="content">
            <p>مرحباً <span class="highlight">{name}</span>،</p>
            
            <p>سعداء جداً باهتمامك بالانضمام إلى نخبة مدربي <span class="highlight">Yemdat</span>. نود إفادتك بأننا قد استلمنا طلبك بنجاح.</p>
            
            <p>يقوم فريقنا الإداري والتقني حالياً بمراجعة تفاصيل مقترحك التدريبي المقدم بعنوان:</p>
            
            <div class="topic-box">
                "{help_topic}"
            </div>

            <p>نحن نقدر عالياً رغبتك في مشاركة خبراتك مع مجتمعنا، وسنقوم بالتواصل معك قريباً لمناقشة الخطوات القادمة عبر الوسائل التالية:</p>
            
            <p>📧 البريد الإلكتروني: <span class="contact-info">{email}</span><br>
               📞 رقم الهاتف: <span class="contact-info">{phone_number}</span></p>
        </div>

        <div class="footer">
            <p>شكراً لكونك جزءاً من رحلة التحول الرقمي</p>
            <strong>فريق Yemdat</strong>
        </div>
    </div>
</body>
</html>',
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
