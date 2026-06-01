<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'phone_code',
        'phone_number',
        'education_level',
        'country',
        'gender',
        'specialty',
        'specialty_other',
        'membership_type',
        'password',
        'bio',
        'linkedin_url',
        'unsubscribed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'otp_expires_at'   => 'datetime',
            'unsubscribed_at'  => 'datetime',
        ];
    }

    /**
     * Get the events the member has registered for.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class , 'event_member', 'member_id', 'event_id')
            ->withPivot('attended_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Certificates issued to this member.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function membershipTier()
    {
        return $this->belongsTo(MembershipTier::class , 'membership_type', 'slug');
    }

    /**
     * Get the contact messages sent by the member.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Profile completeness for the member dashboard.
     *
     * Returns the percent complete plus the keys of any fields still missing,
     * so the profile page can nudge members to finish filling it in.
     *
     * @return array{percent:int, completed:int, total:int, missing:array<int,string>}
     */
    public function profileCompletion(): array
    {
        $fields = [
            'full_name'       => filled($this->full_name),
            'email'           => filled($this->email),
            'phone_number'    => filled($this->phone_number),
            'country'         => filled($this->country),
            'gender'          => filled($this->gender),
            'specialty'       => filled($this->specialty),
            'education_level' => filled($this->education_level),
            'bio'             => filled($this->bio),
            'linkedin_url'    => filled($this->linkedin_url),
        ];

        $total = count($fields);
        $completed = count(array_filter($fields));

        return [
            'percent'   => (int) round($completed / $total * 100),
            'completed' => $completed,
            'total'     => $total,
            'missing'   => array_keys(array_filter($fields, fn ($done) => ! $done)),
        ];
    }
}
