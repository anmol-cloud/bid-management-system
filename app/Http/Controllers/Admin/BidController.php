<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\User;
use App\Support\DataTableHelper;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function index(Request $request)
    {
        $projectManagers = User::where('role', 'project_manager')->orderBy('name')->get();

        return view('admin.bids.index', compact('projectManagers'));
    }

    public function data(Request $request)
    {
        $query = Bid::query()->with(['upworkAccount', 'projectManager']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('project_manager_id')) {
            $query->where('project_manager_id', $request->input('project_manager_id'));
        }

        return DataTableHelper::respond(
            $request,
            $query,
            ['job_title', 'status', 'bid_date'],
            function (Bid $bid) {
                return [
                    'id' => $bid->id,
                    'project_manager' => $bid->projectManager->name ?? '—',
                    'upwork_account' => $bid->upworkAccount->account_name ?? '—',
                    'job_title' => $bid->job_title,
                    'bid_date' => $bid->bid_date->format('d M Y'),
                    'connects_used' => $bid->connects_used,
                    'proposal_amount' => $bid->proposal_amount,
                    'client_budget' => $bid->client_budget,
                    'status' => $bid->status,
                ];
            }
        );
    }
}