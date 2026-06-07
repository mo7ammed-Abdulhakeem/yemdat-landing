<?php

namespace App\Models;

use App\Models\Concerns\HasEmailReplies;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasEmailReplies;

    protected $fillable = [
        'member_id',
        'name',
        'email',
        'phone_number',
        'subject',
        'message',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
