<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class UpdateTrainerEmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $template = EmailTemplate::where('mailable_class', 'TrainerAutoReplyEmail')->first();
        if ($template) {
            $template->update([
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
</html>'
            ]);
        }
    }
}
