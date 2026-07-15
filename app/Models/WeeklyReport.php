<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start', 'week_end', 'total_bids', 'won_bids', 'lost_bids',
        'success_rate', 'file_path', 'sent_to_admin',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'sent_to_admin' => 'boolean',
    ];
}
