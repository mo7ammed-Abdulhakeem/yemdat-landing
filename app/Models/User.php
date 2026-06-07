<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /**
     * Gate Filament panel access by panel + role:
     *  - the `trainer` panel is for trainers only,
     *  - every other panel (the `admin` panel) is for admins / super-admins only.
     * This keeps trainers out of /admin and admins out of /trainer.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'trainer') {
            return $this->role === 'trainer';
        }

        return in_array($this->role, ['admin', 'super_admin'], true);
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'super_admin', 'admin'
        'permissions', // JSON array
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    public function posts()
    {
        return $this->hasMany(Post::class , 'created_by');
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Events this user is assigned to teach (as the trainer/lecturer).
     */
    public function trainedEvents()
    {
        return $this->hasMany(Event::class, 'trainer_id');
    }

    /**
     * Whether this user is a trainer account.
     */
    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }
}
