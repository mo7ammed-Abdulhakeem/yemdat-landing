<?php

namespace App\Mail;

use App\Models\EmailTemplate;

trait DynamicEmailTrait
{
    protected function parseDynamicTemplate(array $data)
    {
        $template = EmailTemplate::where('mailable_class', class_basename(self::class))->first();

        if (!$template) {
            return [
                'from_email' => null,
                'subject' => 'Notification from Yemdat',
                'body' => 'You have a new message from Yemdat.',
                'banner' => null,
            ];
        }

        $locale = app()->getLocale();
        $rawSubject = $locale === 'ar' ? $template->subject_ar : $template->subject_en;
        $rawBody = $locale === 'ar' ? $template->body_ar : $template->body_en;

        $subject = $this->replacePlaceholders($rawSubject, $data);
        $body = $this->replacePlaceholders($rawBody, $data);

        return [
            'from_email' => $template->from_email,
            'subject' => $subject,
            'body' => $body,
            'banner' => $template->banner_image,
        ];
    }

    private function replacePlaceholders($text, $data)
    {
        if (!$text)
            return '';

        foreach ($data as $key => $value) {
            $text = str_replace('{' . $key . '}', $value, $text);
        }
        return $text;
    }
}
