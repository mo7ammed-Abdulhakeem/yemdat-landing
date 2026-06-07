<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial',
        'member_id',
        'event_id',
        'type',
        'issued_by',
        'issued_at',
        'emailed_at',
        'revoked_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'emailed_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate) {
            if (empty($certificate->serial)) {
                $certificate->serial = static::generateSerial();
            }
            if (empty($certificate->issued_at)) {
                $certificate->issued_at = now();
            }
        });
    }

    /**
     * Generate a unique, human-readable, non-enumerable serial: YEM-2026-7K3F9Q
     */
    public static function generateSerial(): string
    {
        do {
            $serial = 'YEM-' . now()->year . '-' . strtoupper(Str::random(6));
        } while (static::where('serial', $serial)->exists());

        return $serial;
    }

    public function isValid(): bool
    {
        return $this->revoked_at === null;
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
