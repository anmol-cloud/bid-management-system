<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpworkAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'upwork_id', 'account_name', 'email', 'profile_url',
        'hourly_rate', 'connects_available', 'status', 'created_by', 'notes',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activeAssignment()
    {
        return $this->hasOne(Assignment::class)->where('is_active', true)->latestOfMany();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isSalesManager()) {
            return $query->whereHas('assignments', function ($q) use ($user) {
                $q->where('is_active', true)->where('sales_manager_id', $user->id);
            });
        }

        // project manager
        return $query->whereHas('assignments', function ($q) use ($user) {
            $q->where('is_active', true)->where('project_manager_id', $user->id);
        });
    }
}
