<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerRequest extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'country',
        'linkedin_url',
        'help_topic',
    ];
}
