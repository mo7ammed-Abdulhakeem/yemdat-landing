<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'email',
        'phone_number',
        'subject',
        'message',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
