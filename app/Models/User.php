<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'status', 'created_by',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ---- Role helpers ----
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSalesManager(): bool
    {
        return $this->role === 'sales_manager';
    }

    public function isProjectManager(): bool
    {
        return $this->role === 'project_manager';
    }

    // ---- Relations ----
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function projectManagers()
    {
        return $this->hasMany(User::class, 'created_by')->where('role', 'project_manager');
    }

    public function createdUpworkAccounts()
    {
        return $this->hasMany(UpworkAccount::class, 'created_by');
    }

    public function assignmentsAsSalesManager()
    {
        return $this->hasMany(Assignment::class, 'sales_manager_id');
    }

    public function assignmentsAsProjectManager()
    {
        return $this->hasMany(Assignment::class, 'project_manager_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'project_manager_id');
    }
}
