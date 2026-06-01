<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'body',
        'background_image',
        'paper',
    ];

    /**
     * The single active certificate template (one design for the whole site).
     * Falls back to the built-in default so a certificate always renders,
     * even before the template row has been seeded.
     */
    public static function current(): self
    {
        $template = static::query()->orderBy('id')->first();

        if ($template && filled($template->body)) {
            return $template;
        }

        return new self([
            'body' => static::defaultBody(),
            'paper' => 'A4-L',
        ]);
    }

    /**
     * Default bilingual (EN/AR) certificate, mPDF-friendly HTML.
     * Tokens: {member_name} {event_title_en} {event_title_ar} {date}
     *         {serial} {verify_url} {qr} {issued_year} {background_url}
     */
    public static function defaultBody(): string
    {
        return <<<'HTML'
<style>
    body { font-family: sans-serif; color: #3a2e25; }
    .frame { border: 4px solid #593E2D; padding: 9px; }
    .inner { border: 1.5px solid #C9A24B; padding: 34px 46px 26px; text-align: center; }
    .brand { color: #593E2D; font-size: 12pt; letter-spacing: 3px; font-weight: bold; }
    .brand-ar { color: #8a6d4f; font-size: 13pt; margin-top: 2px; }
    .title { color: #593E2D; font-size: 30pt; font-weight: bold; margin: 16px 0 0; }
    .title-ar { color: #593E2D; font-size: 21pt; margin: 0 0 12px; }
    .muted { color: #7a6a5c; font-size: 11pt; }
    .name { color: #1f2937; font-size: 26pt; font-weight: bold; margin: 10px 0 4px; }
    .rule { border-bottom: 1px solid #C9A24B; width: 55%; margin: 4px auto 14px; }
    .event { font-size: 15pt; font-weight: bold; color: #3a2e25; margin-top: 2px; }
    .date { font-size: 11pt; color: #7a6a5c; margin-top: 12px; }
    .org { font-size: 11pt; font-weight: bold; color: #593E2D; }
    .foot-label { font-size: 8.5pt; color: #7a6a5c; }
    .serial { font-size: 9.5pt; color: #593E2D; font-weight: bold; }
</style>

<div class="frame">
    <div class="inner">
        <div class="brand">YEMDAT DATA COMMUNITY</div>
        <div class="brand-ar" dir="rtl">مجتمع يمدات للبيانات</div>

        <div class="title">Certificate of Completion</div>
        <div class="title-ar" dir="rtl">شهادة إتمام</div>

        <div class="muted">This is to certify that &nbsp;&middot;&nbsp; <span dir="rtl">نشهد بأن</span></div>
        <div class="name">{member_name}</div>
        <div class="rule"></div>

        <div class="muted">has successfully completed the program &nbsp;&middot;&nbsp; <span dir="rtl">قد أتمّ بنجاح برنامج</span></div>
        <div class="event">{event_title_en}</div>
        <div class="event" dir="rtl">{event_title_ar}</div>

        <div class="date">Completed on {date} &nbsp;&middot;&nbsp; <span dir="rtl">بتاريخ {date}</span></div>

        <table width="100%" style="margin-top: 24px;"><tr>
            <td width="65%" align="left" style="vertical-align: bottom;">
                <div class="org">Yemdat Data Community &nbsp;|&nbsp; <span dir="rtl">مجتمع يمدات</span></div>
                <div class="foot-label">Verify this certificate at {verify_url}</div>
                <div class="serial">Serial: {serial}</div>
            </td>
            <td width="35%" align="right" style="vertical-align: bottom;">
                {qr}
            </td>
        </tr></table>
    </div>
</div>
HTML;
    }
}
