<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeMember(array $overrides = []): Member
    {
        return Member::create(array_merge([
            'full_name' => 'Dash Member',
            'email' => 'dash@example.com',
            'phone_code' => '+967',
            'phone_number' => '711111111',
            'country' => 'Yemen',
            'gender' => 'Male',
            'specialty' => 'Data Analysis',
            'membership_type' => 'member',
            'password' => 'password',
        ], $overrides));
    }

    private function makeEvent(array $overrides = []): Event
    {
        static $i = 0;
        $i++;

        return Event::create(array_merge([
            'title_en' => 'Event '.$i,
            'title_ar' => 'فعالية '.$i,
            'slug' => 'event-'.$i,
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeek()->addHours(2),
            'location' => "Sana'a, Yemen",
            'is_active' => true,
        ], $overrides));
    }

    public function test_profile_completion_counts_filled_fields(): void
    {
        // Required fields filled, but education_level / bio / linkedin_url missing => 6/9.
        $member = $this->makeMember();

        $completion = $member->profileCompletion();

        $this->assertSame(9, $completion['total']);
        $this->assertSame(6, $completion['completed']);
        $this->assertSame(67, $completion['percent']);
        $this->assertEqualsCanonicalizing(
            ['education_level', 'bio', 'linkedin_url'],
            $completion['missing']
        );
    }

    public function test_profile_completion_reaches_100_when_all_filled(): void
    {
        $member = $this->makeMember([
            'education_level' => 'Bachelor',
            'bio' => 'Hello there.',
            'linkedin_url' => 'https://linkedin.com/in/dash',
        ]);

        $completion = $member->profileCompletion();

        $this->assertSame(100, $completion['percent']);
        $this->assertSame([], $completion['missing']);
    }

    public function test_dashboard_splits_upcoming_and_past_events(): void
    {
        $member = $this->makeMember();
        $upcoming = $this->makeEvent(['start_date' => now()->addDays(5), 'end_date' => now()->addDays(5)->addHour()]);
        $past = $this->makeEvent(['start_date' => now()->subDays(5), 'end_date' => now()->subDays(5)->addHour()]);

        $member->events()->attach([$upcoming->id, $past->id]);

        $response = $this->actingAs($member, 'member')->get(route('profile.show'));

        $response->assertOk();
        $response->assertViewHas('upcomingEvents', fn ($events) => $events->pluck('id')->all() === [$upcoming->id]);
        $response->assertViewHas('pastEvents', fn ($events) => $events->pluck('id')->all() === [$past->id]);
        $response->assertViewHas('nextEvent', fn ($event) => $event->id === $upcoming->id);
        $response->assertSee($upcoming->title);
        $response->assertSee($past->title);
    }

    public function test_dashboard_shows_certificate_download_for_valid_certificate(): void
    {
        $member = $this->makeMember();
        $event = $this->makeEvent(['start_date' => now()->subDays(5), 'end_date' => now()->subDays(5)->addHour()]);
        $member->events()->attach($event->id);

        $cert = Certificate::create([
            'member_id' => $member->id,
            'event_id' => $event->id,
            'type' => 'completion',
        ]);

        $response = $this->actingAs($member, 'member')->get(route('profile.show'));

        $response->assertOk();
        $response->assertViewHas('certificatesByEvent', fn ($map) => $map->has($event->id));
        $response->assertSee(route('certificates.download', $cert), false);
    }

    public function test_revoked_certificate_is_not_offered_for_download(): void
    {
        $member = $this->makeMember();
        $event = $this->makeEvent(['start_date' => now()->subDays(5), 'end_date' => now()->subDays(5)->addHour()]);
        $member->events()->attach($event->id);

        Certificate::create([
            'member_id' => $member->id,
            'event_id' => $event->id,
            'type' => 'completion',
            'revoked_at' => now(),
        ]);

        $response = $this->actingAs($member, 'member')->get(route('profile.show'));

        $response->assertOk();
        $response->assertViewHas('certificatesByEvent', fn ($map) => $map->isEmpty());
    }
}
