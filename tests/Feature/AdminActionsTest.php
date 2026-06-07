<?php

namespace Tests\Feature;

use App\Actions\Certificates\IssueCertificate;
use App\Actions\Certificates\SendCertificateEmail;
use App\Actions\SendAdminReply;
use App\Mail\AdminReplyEmail;
use App\Mail\CertificateIssuedEmail;
use App\Models\Certificate;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Member;
use App\Models\TrainerRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminActionsTest extends TestCase
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

    private function completedCertificate(): Certificate
    {
        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        return app(IssueCertificate::class)->execute($event, $member);
    }

    private function makeContact(array $overrides = []): Contact
    {
        return Contact::create(array_merge([
            'name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'subject' => 'Question about training',
            'message' => 'Tell me more.',
        ], $overrides));
    }

    private function makeTrainerRequest(array $overrides = []): TrainerRequest
    {
        return TrainerRequest::create(array_merge([
            'name' => 'Omar Trainer',
            'email' => 'omar@example.com',
            'phone_number' => '+967700111222',
            'program_type' => 'workshop',
            'duration_days' => 2,
            'duration_hours' => 6,
            'agenda' => '<p>Agenda</p>',
            'agreed_to_free_provision' => true,
        ], $overrides));
    }

    // ---- Admin reply -----------------------------------------------------

    public function test_admin_reply_sends_email_records_history_and_sets_status(): void
    {
        Mail::fake();

        $contact = $this->makeContact();

        app(SendAdminReply::class)->execute($contact, [
            'from_name' => 'Yemdat Support',
            'from_email' => 'support@yemdat.com',
            'subject' => 'Re: Question about training',
            'body' => '<p>Hello Sara</p>',
        ]);

        Mail::assertSent(AdminReplyEmail::class, fn ($mail) => $mail->hasTo('sara@example.com'));

        $this->assertDatabaseHas('email_replies', [
            'replyable_type' => Contact::class,
            'replyable_id' => $contact->id,
            'from_email' => 'support@yemdat.com',
            'subject' => 'Re: Question about training',
        ]);

        $this->assertSame(Contact::STATUS_REPLIED, $contact->fresh()->status);
    }

    public function test_admin_reply_uses_authorized_from_and_typed_reply_to(): void
    {
        // The mailable must send from the authorized mailbox (SPF/DKIM aligned on
        // shared hosting) and route the admin's typed address to Reply-To.
        $mail = new AdminReplyEmail('Yemdat Support', 'support@yemdat.com', 'Re: x', '<p>Hi</p>');
        $mail->build();

        $this->assertSame(config('mail.from.address'), $mail->from[0]['address']);
        $this->assertSame('Yemdat Support', $mail->from[0]['name']);
        $this->assertSame('support@yemdat.com', $mail->replyTo[0]['address']);
    }

    public function test_admin_reply_works_for_trainer_requests(): void
    {
        Mail::fake();

        $request = $this->makeTrainerRequest();

        app(SendAdminReply::class)->execute($request, [
            'from_name' => 'Yemdat',
            'from_email' => 'team@yemdat.com',
            'subject' => 'Re: Your trainer application',
            'body' => '<p>Thanks</p>',
        ]);

        Mail::assertSent(AdminReplyEmail::class, fn ($mail) => $mail->hasTo('omar@example.com'));
        $this->assertSame(1, $request->replies()->count());
        $this->assertSame(TrainerRequest::STATUS_REPLIED, $request->fresh()->status);
    }

    // ---- Certificate email ----------------------------------------------

    public function test_send_certificate_email_sends_and_stamps_emailed_at(): void
    {
        Mail::fake();

        $cert = $this->completedCertificate();
        // completedCertificate already auto-sent once on issue; reset the clock.
        $cert->forceFill(['emailed_at' => null])->save();
        Mail::fake(); // clear previous recordings

        app(SendCertificateEmail::class)->execute($cert);

        Mail::assertSent(CertificateIssuedEmail::class, fn ($mail) => $mail->hasTo($cert->member->email));
        $this->assertNotNull($cert->fresh()->emailed_at);
    }

    public function test_certificate_email_is_auto_sent_only_on_first_issue(): void
    {
        Mail::fake();

        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        app(IssueCertificate::class)->execute($event, $member); // creates -> auto-send
        app(IssueCertificate::class)->execute($event, $member); // idempotent -> no send

        Mail::assertSent(CertificateIssuedEmail::class, 1);
    }

    public function test_bulk_issue_does_not_auto_email(): void
    {
        // The bulk "Issue certificates (completed)" action passes autoEmail: false
        // so it never fires one synchronous email per certificate on shared hosting.
        Mail::fake();

        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        $cert = app(IssueCertificate::class)->execute($event, $member, null, autoEmail: false);

        $this->assertNotNull($cert->serial);
        $this->assertNull($cert->emailed_at);
        Mail::assertNotSent(CertificateIssuedEmail::class);
    }

    public function test_inactive_template_disables_auto_send(): void
    {
        Mail::fake();

        EmailTemplate::create([
            'mailable_class' => 'CertificateIssuedEmail',
            'subject_en' => 'Certificate',
            'subject_ar' => 'شهادة',
            'body_en' => 'Body',
            'body_ar' => 'محتوى',
            'is_active' => false,
        ]);

        $member = $this->makeMember();
        $event = $this->makeEvent();
        $event->members()->attach($member->id, ['completed_at' => now()]);

        app(IssueCertificate::class)->execute($event, $member);

        Mail::assertNotSent(CertificateIssuedEmail::class);
    }

    // ---- New-submission admin notifications ------------------------------

    public function test_contact_submission_notifies_admins(): void
    {
        Mail::fake();
        User::factory()->create(['role' => 'super_admin']);

        $this->from(route('contact'))->post(route('contact.store'), [
            'name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'subject' => 'Hi',
            'message' => 'A message',
        ])->assertRedirect(route('contact'));

        $this->assertDatabaseCount('notifications', 1);
    }

    // ---- Filament surfaces still load ------------------------------------

    public function test_admin_pages_load(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $contact = $this->makeContact();
        $request = $this->makeTrainerRequest();
        $member = $this->makeMember();
        $template = EmailTemplate::create([
            'mailable_class' => 'WelcomeEmail',
            'subject_en' => 'Welcome', 'subject_ar' => 'أهلا',
            'body_en' => 'Hi {name}', 'body_ar' => 'مرحبا {name}',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get('/admin/contacts')->assertOk();
        $this->actingAs($admin)->get('/admin/contacts/'.$contact->id)->assertOk();
        $this->actingAs($admin)->get('/admin/trainer-requests')->assertOk();
        $this->actingAs($admin)->get('/admin/trainer-requests/'.$request->id)->assertOk();
        $this->actingAs($admin)->get('/admin/members')->assertOk();
        $this->actingAs($admin)->get('/admin/members/'.$member->id)->assertOk();
        $this->actingAs($admin)->get('/admin/members/'.$member->id.'/edit')->assertOk();
        $this->actingAs($admin)->get('/admin/email-templates')->assertOk();
        $this->actingAs($admin)->get('/admin/email-templates/create')->assertOk();
        $this->actingAs($admin)->get('/admin/email-templates/'.$template->id)->assertOk();
    }
}
