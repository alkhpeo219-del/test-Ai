<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'employee_code', 'name', 'first_name_lao', 'last_name_lao',
        'email', 'password', 'avatar_path', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /** Flat permission names via roles */
    public function permissionNames(): array
    {
        return $this->roles()->with('permissions')->get()
            ->pluck('permissions')->flatten()->pluck('name')->unique()->values()->all();
    }

    public function hasRole(string $name): bool
    {
        return $this->roles->contains('name', $name);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /** '*' wildcard or exact match */
    public function can($abilities, $arguments = []): bool
    {
        if (is_string($abilities) && !class_exists($abilities) && str_contains($abilities, '.')) {
            if ($this->isSuperAdmin()) return true;
            if (!$this->relationLoaded('roles')) $this->load('roles.permissions');
            foreach ($this->roles as $role) {
                foreach ($role->permissions as $perm) {
                    if ($perm->name === $abilities || $perm->name === '*') return true;
                }
            }
            return false;
        }
        return parent::can($abilities, $arguments);
    }

    public function teacher()  { return $this->hasOne(Teacher::class); }
    public function student()  { return $this->hasOne(Student::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
