<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Renders a Certificate to a bilingual PDF using the admin-editable
 * DB template (mPDF, pure-PHP — safe on shared hosting). The QR code is
 * produced by mPDF's built-in <barcode type="QR"> tag (via mpdf/qrcode).
 */
class CertificatePdf
{
    public function render(Certificate $certificate): string
    {
        $certificate->loadMissing('member', 'event');
        $template = CertificateTemplate::current();

        $html = $this->replacePlaceholders($template->body, $this->tokens($certificate, $template));

        return $this->build($html, $template->paper ?: 'A4-L');
    }

    /**
     * Render a sample certificate (dummy data) — used by the template editor's
     * "Preview" so admins can see unsaved edits.
     */
    public function renderSample(?string $body = null, ?string $paper = null, ?string $backgroundImage = null): string
    {
        $body ??= CertificateTemplate::defaultBody();
        $html = $this->replacePlaceholders($body, $this->sampleTokens($backgroundImage));

        return $this->build($html, $paper ?: 'A4-L');
    }

    protected function build(string $html, string $paper): string
    {
        $tempDir = storage_path('app/mpdf');
        if (! is_dir($tempDir)) {
            @mkdir($tempDir, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $paper,
            'tempDir' => $tempDir,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_top' => 12,
            'margin_bottom' => 12,
        ]);

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * @return array<string,string>
     */
    protected function tokens(Certificate $certificate, CertificateTemplate $template): array
    {
        $member = $certificate->member;
        $event = $certificate->event;
        $issuedAt = $certificate->issued_at ?? $certificate->created_at;
        $verifyUrl = route('certificates.verify', $certificate->serial);

        return [
            'member_name' => e($member?->full_name ?? ''),
            'event_title_en' => e($event?->title_en ?? ''),
            'event_title_ar' => e($event?->title_ar ?? ''),
            'date' => optional($issuedAt)->format('F j, Y') ?? '',
            'issued_year' => optional($issuedAt)->format('Y') ?? '',
            'serial' => e($certificate->serial),
            'verify_url' => e($verifyUrl),
            'qr' => $this->qrTag($verifyUrl),
            'background_url' => $this->backgroundPath($template->background_image),
        ];
    }

    /**
     * @return array<string,string>
     */
    protected function sampleTokens(?string $backgroundImage): array
    {
        $verifyUrl = url('/verify/YEM-'.now()->year.'-SAMPLE');

        return [
            'member_name' => 'Member Name / اسم العضو',
            'event_title_en' => 'Data Analysis using Python',
            'event_title_ar' => 'تحليل البيانات باستخدام بايثون',
            'date' => now()->format('F j, Y'),
            'issued_year' => now()->format('Y'),
            'serial' => 'YEM-'.now()->year.'-SAMPLE',
            'verify_url' => e($verifyUrl),
            'qr' => $this->qrTag($verifyUrl),
            'background_url' => $this->backgroundPath($backgroundImage),
        ];
    }

    protected function qrTag(string $url): string
    {
        return '<barcode code="'.$url.'" type="QR" error="M" disableborder="1" size="1.1" />';
    }

    protected function backgroundPath(?string $image): string
    {
        if (! $image) {
            return '';
        }

        $path = Storage::disk('public')->path($image);

        return is_file($path) ? $path : '';
    }

    protected function replacePlaceholders(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace('{'.$key.'}', (string) $value, $text);
        }

        return $text;
    }
}
