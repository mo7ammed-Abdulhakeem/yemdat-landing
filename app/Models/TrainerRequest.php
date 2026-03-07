<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerRequest extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'linkedin_url',
        'country',
        'program_type',
        'duration_hours',
        'duration_days',
        'agenda',
        'agreed_to_free_provision',
    ];
}
