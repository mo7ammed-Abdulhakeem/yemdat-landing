<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Don't clobber an admin's customised design on re-seed.
        if (CertificateTemplate::query()->exists()) {
            return;
        }

        CertificateTemplate::create([
            'paper' => 'A4-L',
            'body' => CertificateTemplate::defaultBody(),
        ]);
    }
}
