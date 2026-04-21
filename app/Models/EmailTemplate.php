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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function isActiveFor(string $mailable): bool
    {
        $template = static::where('mailable_class', $mailable)->first();
        return !$template || $template->is_active;
    }
}
