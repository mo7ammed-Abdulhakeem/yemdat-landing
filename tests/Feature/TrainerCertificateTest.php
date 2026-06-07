<?php

namespace Tests\Feature;

use App\Actions\Certificates\IssueCertificate;
use App\Filament\Trainer\Resources\Certificates\TrainerCertificateResource;
use App\Models\Event;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TrainerCertificateTest extends TestCase
{
    use RefreshDatabase;

    private function makeTrainer(string $email): User
    {
        return User::create([
            'name' => 'Trainer '.$email,
            'email' => $email,
            'password' => 'password',
            'role' => 'trainer',
        ]);
    }

    private function makeMember(string $email, string $phone): Member
    {
        return Member::create([
            'full_name' => 'Member '.$email,
            'email' => $email,
            'phone_code' => '+967',
            'phone_number' => $phone,
            'country' => 'Yemen',
            'specialty' => 'data-analytics',
            'membership_type' => 'member',
            'password' => 'password',
        ]);
    }

    private function makeEvent(string $slug, User $trainer): Event
    {
        return Event::create([
            'title_en' => 'Event '.$slug,
            'title_ar' => 'فعالية '.$slug,
            'slug' => $slug,
            'start_date' => now()->subWeek(),
            'end_date' => now()->subWeek()->addHours(2),
            'is_active' => true,
            'trainer_id' => $trainer->id,
        ]);
    }

    private function issueFor(Event $event, Member $member, User $issuer)
    {
        $event->members()->attach($member->id, ['completed_at' => now()]);

        return app(IssueCertificate::class)->execute($event, $member, $issuer);
    }

    public function test_certificate_issued_by_a_trainer_records_the_trainer_as_issuer(): void
    {
        Mail::fake();
        $t1 = $this->makeTrainer('ct1@example.com');
        $event = $this->makeEvent('cert-evt', $t1);
        $member = $this->makeMember('cm1@example.com', '700111222');

        $cert = $this->issueFor($event, $member, $t1);

        $this->assertSame($t1->id, $cert->issued_by);
    }

    public function test_certificate_query_is_scoped_to_the_trainers_events(): void
    {
        Mail::fake();
        $t1 = $this->makeTrainer('cs1@example.com');
        $t2 = $this->makeTrainer('cs2@example.com');

        $e1 = $this->makeEvent('cs-e1', $t1);
        $e2 = $this->makeEvent('cs-e2', $t2);

        $mineCert = $this->issueFor($e1, $this->makeMember('csm1@example.com', '700333444'), $t1);
        $theirsCert = $this->issueFor($e2, $this->makeMember('csm2@example.com', '700555666'), $t2);

        $this->actingAs($t1);
        $ids = TrainerCertificateResource::getEloquentQuery()->pluck('id');

        $this->assertTrue($ids->contains($mineCert->id));
        $this->assertFalse($ids->contains($theirsCert->id));

        // And the view page 404s for another trainer's certificate.
        $this->actingAs($t1)
            ->get('/trainer/certificates/trainer-certificates/'.$theirsCert->id)
            ->assertNotFound();
    }
}
