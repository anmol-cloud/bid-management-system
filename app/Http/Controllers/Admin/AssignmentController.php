<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentLog;
use App\Models\UpworkAccount;
use App\Models\User;
use App\Support\DataTableHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function index()
    {
        $salesManagers = User::where('role', 'sales_manager')->orderBy('name')->get();
        $projectManagers = User::where('role', 'project_manager')->orderBy('name')->get();
        $upworkAccounts = UpworkAccount::orderBy('account_name')->get();

        return view('admin.assignments.index', compact('salesManagers', 'projectManagers', 'upworkAccounts'));
    }

    public function data(Request $request)
    {
        $query = UpworkAccount::query()->with(['activeAssignment.salesManager', 'activeAssignment.projectManager']);

        return DataTableHelper::respond(
            $request,
            $query,
            ['account_name', 'upwork_id', 'status'],
            function (UpworkAccount $account) {
                $assignment = $account->activeAssignment;

                return [
                    'id' => $account->id,
                    'account_name' => $account->account_name,
                    'upwork_id' => $account->upwork_id,
                    'sales_manager' => $assignment?->salesManager?->name ?? '—',
                    'sales_manager_id' => $assignment?->sales_manager_id,
                    'project_manager' => $assignment?->projectManager?->name ?? '—',
                    'project_manager_id' => $assignment?->project_manager_id,
                    'status' => $account->status,
                ];
            }
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'upwork_account_id' => ['required', 'exists:upwork_accounts,id'],
            'sales_manager_id' => ['nullable', 'exists:users,id'],
            'project_manager_id' => ['nullable', 'exists:users,id'],
        ]);

        DB::transaction(function () use ($data, $request) {
            Assignment::where('upwork_account_id', $data['upwork_account_id'])
                ->where('is_active', true)
                ->update(['is_active' => false]);

            Assignment::create([
                'upwork_account_id' => $data['upwork_account_id'],
                'sales_manager_id' => $data['sales_manager_id'] ?? null,
                'project_manager_id' => $data['project_manager_id'] ?? null,
                'assigned_by' => $request->user()->id,
                'is_active' => true,
                'assigned_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Assignment update ho gaya hai.']);
    }
}
