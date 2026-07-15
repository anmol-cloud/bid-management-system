<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentLog extends Model
{
    protected $fillable = [
        'upwork_account_id', 'from_user_id', 'to_user_id', 'changed_by', 'role_type',
    ];
}
