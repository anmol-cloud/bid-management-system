<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'upwork_account_id', 'project_manager_id', 'job_title', 'bid_date',
        'connects_used', 'proposal_amount', 'client_budget', 'status', 'notes',
    ];

    protected $casts = [
        'bid_date' => 'date',
    ];

    public function upworkAccount()
    {
        return $this->belongsTo(UpworkAccount::class);
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }
}
