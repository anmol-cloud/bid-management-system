<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'upwork_account_id', 'sales_manager_id', 'project_manager_id',
        'assigned_by', 'is_active', 'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function upworkAccount()
    {
        return $this->belongsTo(UpworkAccount::class);
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
