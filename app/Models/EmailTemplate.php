<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'mailable_class',
        'from_email',
        'subject_en',
        'subject_ar',
        'body_en',
        'body_ar',
        'banner_image',
    ];
}
