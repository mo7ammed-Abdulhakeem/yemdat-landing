<?php

namespace Tests\Feature;

use App\Filament\Trainer\Resources\Events\TrainerEventResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerEventScopeTest extends TestCase
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

    private function makeEvent(string $slug, ?User $trainer): Event
    {
        return Event::create([
            'title_en' => 'Event '.$slug,
            'title_ar' => 'فعالية '.$slug,
            'slug' => $slug,
            'start_date' => now()->addWeek(),
            'is_active' => true,
            'trainer_id' => $trainer?->id,
        ]);
    }

    public function test_query_is_scoped_to_the_acting_trainer(): void
    {
        $t1 = $this->makeTrainer('t1@example.com');
        $t2 = $this->makeTrainer('t2@example.com');
        $mine = $this->makeEvent('mine', $t1);
        $theirs = $this->makeEvent('theirs', $t2);
        $unassigned = $this->makeEvent('unassigned', null);

        $this->actingAs($t1);
        $ids = TrainerEventResource::getEloquentQuery()->pluck('id');

        $this->assertTrue($ids->contains($mine->id));
        $this->assertFalse($ids->contains($theirs->id));
        $this->assertFalse($ids->contains($unassigned->id));
    }

    public function test_trainer_can_open_own_event_but_not_another_trainers(): void
    {
        $t1 = $this->makeTrainer('o1@example.com');
        $t2 = $this->makeTrainer('o2@example.com');
        $mine = $this->makeEvent('o-mine', $t1);
        $theirs = $this->makeEvent('o-theirs', $t2);

        $this->actingAs($t1)
            ->get('/trainer/events/trainer-events/'.$mine->id)
            ->assertOk()
            ->assertSee('Event o-mine');

        $this->actingAs($t1)
            ->get('/trainer/events/trainer-events/'.$theirs->id)
            ->assertNotFound();
    }

    public function test_my_events_is_read_only(): void
    {
        $this->assertFalse(TrainerEventResource::canCreate());

        $t1 = $this->makeTrainer('ro@example.com');
        $event = $this->makeEvent('ro-evt', $t1);

        // No create/edit pages are registered, so those routes don't exist.
        $this->actingAs($t1)
            ->get('/trainer/events/trainer-events/'.$event->id.'/edit')
            ->assertNotFound();
    }
}
