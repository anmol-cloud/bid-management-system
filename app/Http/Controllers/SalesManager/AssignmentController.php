<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\UpworkAccount;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function assignToPm(Request $request)
    {
        $data = $request->validate([
            'upwork_account_id' => ['required', 'exists:upwork_accounts,id'],
            'project_manager_id' => ['required', 'exists:users,id'],
        ]);

        $existing = Assignment::where('upwork_account_id', $data['upwork_account_id'])
            ->where('is_active', true)
            ->first();

        Assignment::create([
            'upwork_account_id' => $data['upwork_account_id'],
            'sales_manager_id' => $request->user()->id,
            'project_manager_id' => $data['project_manager_id'],
            'assigned_by' => $request->user()->id,
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        if ($existing) {
            $existing->update(['is_active' => false]);
        }

        $pm = User::find($data['project_manager_id']);
        $pm?->notify(new \App\Notifications\AssignmentNotification(
            UpworkAccount::find($data['upwork_account_id'])
        ));

        return response()->json(['message' => 'Upwork ID Project Manager ko assign kar diya gaya hai.']);
    }
}
