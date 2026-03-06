<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasUuids;
    protected $fillable = [
        'title_en',
        'title_ar',
        'slug',
        'content_en',
        'content_ar',
        'type',
        'image',
        'tags',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class , 'created_by');
    }

    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' && !empty($this->title_ar) ? $this->title_ar : $this->title_en;
    }

    public function getContentAttribute()
    {
        return app()->getLocale() === 'ar' && !empty($this->content_ar) ? $this->content_ar : $this->content_en;
    }
}
