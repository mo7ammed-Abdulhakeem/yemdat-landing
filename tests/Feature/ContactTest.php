<?php

namespace Tests\Feature;

use App\Mail\ContactUsAdminAlert;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_a_valid_contact_message(): void
    {
        Mail::fake();

        $response = $this->from(route('contact'))->post(route('contact.store'), [
            'name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'phone_number' => '+967700000000',
            'subject' => 'Question about training',
            'message' => 'I would like to know more about your data courses.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'email' => 'sara@example.com',
            'subject' => 'Question about training',
        ]);

        Mail::assertQueued(ContactUsAdminAlert::class);
    }

    public function test_contact_submission_requires_core_fields(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertSame(0, Contact::count());
    }
}
