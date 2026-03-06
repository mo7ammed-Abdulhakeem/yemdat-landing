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
            'otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the events the member has registered for.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
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
}
