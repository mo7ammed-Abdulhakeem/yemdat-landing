<?php

namespace Tests\Feature;

use App\Actions\Certificates\IssueCertificate;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Member;
use App\Models\User;
use App\Services\CertificatePdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    private int $seq = 0;

    private function makeMember(array $overrides = []): Member
    {
        $this->seq++;

        return Member::create(array_merge([
            'full_name' => 'Test Member '.$this->seq,
            'email' => 'member'.$this->seq.'@example.com',
            'phone_code' => '+967',
            'phone_number' => '70'.str_pad((string) $this->seq, 7, '0', STR_PAD_LEFT),
            'country' => 'Yemen',
            'specialty' => 'Data Analysis',
            'membership_type' => 'member',
            'password' => 'password',
        ], $overrides));
    }

    private function makeEvent(array $overrides = []): Event
    {
        $this->seq++;

        return Event::create(array_merge([
            'title_en' => 'Intro to Data',
            'title_ar' => 'مقدمة في البيانات',
            'slug' => 'intro-to-data-'.$this->seq,
            'start_date' => now()->subWeek(),
            'end_date' => now()->subWeek()->addHours(2),
            'location' => "Sana'a, Yemen",
            'is_active' => true,
        ], $overrides));
    }

    private function issuedCertificate(?Member $member = null, ?Event $event = null): Certificate
    {
        $member ??= $this->makeMember();
        $event ??= $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        return app(IssueCertificate::class)->execute($event, $member);
    }

    // ---- IssueCertificate action ----------------------------------------

    public function test_issues_certificate_for_a_completed_attendee(): void
    {
        $admin = User::factory()->create();
        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        $cert = app(IssueCertificate::class)->execute($event, $member, $admin);

        $this->assertMatchesRegularExpression('/^YEM-\d{4}-[A-Z0-9]{6}$/', $cert->serial);
        $this->assertDatabaseHas('certificates', [
            'member_id' => $member->id,
            'event_id' => $event->id,
            'issued_by' => $admin->id,
            'type' => 'completion',
        ]);
    }

    public function test_blocks_issue_when_not_completed(): void
    {
        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id); // registered, not completed

        $this->expectException(RuntimeException::class);
        app(IssueCertificate::class)->execute($event, $member);
    }

    public function test_issue_is_idempotent(): void
    {
        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        $first = app(IssueCertificate::class)->execute($event, $member);
        $second = app(IssueCertificate::class)->execute($event, $member);

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, Certificate::where('member_id', $member->id)->where('event_id', $event->id)->count());
    }

    public function test_serials_are_unique(): void
    {
        $a = $this->issuedCertificate();
        $b = $this->issuedCertificate();

        $this->assertNotSame($a->serial, $b->serial);
    }

    // ---- PDF rendering ---------------------------------------------------

    public function test_pdf_renders_with_pdf_header(): void
    {
        $cert = $this->issuedCertificate();

        $pdf = app(CertificatePdf::class)->render($cert);

        $this->assertStringStartsWith('%PDF', $pdf);
    }

    // ---- Member download -------------------------------------------------

    public function test_owner_can_download_certificate(): void
    {
        $member = $this->makeMember();
        $cert = $this->issuedCertificate($member);

        $response = $this->actingAs($member, 'member')->get(route('certificates.download', $cert));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_non_owner_cannot_download_certificate(): void
    {
        $cert = $this->issuedCertificate();
        $other = $this->makeMember();

        $this->actingAs($other, 'member')
            ->get(route('certificates.download', $cert))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_from_download(): void
    {
        $cert = $this->issuedCertificate();

        $this->get(route('certificates.download', $cert))->assertRedirect();
    }

    public function test_revoked_certificate_cannot_be_downloaded(): void
    {
        $member = $this->makeMember();
        $cert = $this->issuedCertificate($member);
        $cert->update(['revoked_at' => now()]);

        $this->actingAs($member, 'member')
            ->get(route('certificates.download', $cert))
            ->assertStatus(410);
    }

    // ---- Public verify ---------------------------------------------------

    public function test_verify_shows_valid_certificate(): void
    {
        $cert = $this->issuedCertificate();

        $this->get(route('certificates.verify', $cert->serial))
            ->assertOk()
            ->assertSee('Valid certificate')
            ->assertSee($cert->serial);
    }

    public function test_verify_shows_revoked_certificate(): void
    {
        $cert = $this->issuedCertificate();
        $cert->update(['revoked_at' => now()]);

        $this->get(route('certificates.verify', $cert->serial))
            ->assertOk()
            ->assertSee('Certificate revoked');
    }

    public function test_verify_unknown_serial_shows_not_found(): void
    {
        $this->get(route('certificates.verify', 'YEM-9999-NOPE00'))
            ->assertOk()
            ->assertSee('Certificate not found');
    }

    // ---- Filament surfaces ----------------------------------------------

    public function test_certificate_resource_index_and_view_load(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $cert = $this->issuedCertificate();

        $this->actingAs($admin)->get('/admin/certificates')->assertOk();
        $this->actingAs($admin)->get('/admin/certificates/'.$cert->id)->assertOk();
    }

    public function test_event_view_page_with_attendees_relation_manager_loads(): void
    {
        // The attendees/certificate roster now lives on the View page (not Edit).
        $admin = User::factory()->create(['role' => 'super_admin']);
        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        $this->actingAs($admin)->get('/admin/events/'.$event->id)->assertOk();
        $this->actingAs($admin)->get('/admin/events/'.$event->id.'/edit')->assertOk();
    }

    public function test_manage_certificate_template_page_loads(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)->get('/admin/manage-certificate-template')->assertOk();
    }
}
