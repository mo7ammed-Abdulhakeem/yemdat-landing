<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

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
    ];
    public function membershipTier()
    {
        return $this->belongsTo(MembershipTier::class , 'membership_type', 'slug');
    }
}
