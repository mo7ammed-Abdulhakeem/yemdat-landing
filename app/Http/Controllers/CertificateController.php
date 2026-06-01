<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\CertificatePdf;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Stream the certificate PDF to its owning member.
     */
    public function download(Certificate $certificate, CertificatePdf $pdf)
    {
        $member = Auth::guard('member')->user();

        abort_unless($member && $certificate->member_id === $member->id, 403);
        abort_if($certificate->revoked_at !== null, 410);

        return response($pdf->render($certificate), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="certificate-'.$certificate->serial.'.pdf"',
        ]);
    }

    /**
     * Public certificate verification page (the target of the QR code).
     */
    public function verify(string $serial)
    {
        $certificate = Certificate::where('serial', $serial)
            ->with(['member', 'event'])
            ->first();

        return view('certificates.verify', compact('certificate', 'serial'));
    }
}
