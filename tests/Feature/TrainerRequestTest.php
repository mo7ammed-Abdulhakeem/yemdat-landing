<?php

namespace Tests\Feature;

use App\Mail\TrainerRequestNotification;
use App\Models\TrainerRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TrainerRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Trainer One',
            'phone_number' => '+967700000000',
            'email' => 'trainer@example.com',
            'linkedin_url' => 'https://linkedin.com/in/trainer',
            'program_type' => 'workshop',
            'duration_days' => 2,
            'duration_hours' => 6,
            'agenda' => 'Day 1 intro, Day 2 hands-on practice.',
            'agreed_to_free_provision' => '1',
        ], $overrides);
    }

    public function test_guest_can_submit_a_trainer_request(): void
    {
        Mail::fake();

        $response = $this->from(route('trainer.create'))
            ->post(route('trainer.store'), $this->validPayload());

        $response->assertRedirect(route('trainer.create'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('trainer_requests', [
            'email' => 'trainer@example.com',
            'program_type' => 'workshop',
        ]);
        Mail::assertQueued(TrainerRequestNotification::class);
    }

    public function test_trainer_request_validates_input(): void
    {
        Mail::fake();

        $response = $this->from(route('trainer.create'))->post(route('trainer.store'), $this->validPayload([
            'name' => '',
            'email' => 'not-an-email',
            'program_type' => 'seminar', // not in workshop,course
            'duration_days' => 0,        // min:1
            'agenda' => '',
        ]));

        $response->assertSessionHasErrors(['name', 'email', 'program_type', 'duration_days', 'agenda']);
        $this->assertSame(0, TrainerRequest::count());
        Mail::assertNothingSent();
    }
}
