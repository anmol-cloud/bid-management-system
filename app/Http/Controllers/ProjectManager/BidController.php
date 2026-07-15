<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\UpworkAccount;
use App\Support\DataTableHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BidController extends Controller
{
    public function index(Request $request)
    {
        $myAccounts = UpworkAccount::visibleTo($request->user())->orderBy('account_name')->get();

        return view('project-manager.bids.index', compact('myAccounts'));
    }

    public function data(Request $request)
    {
        $query = Bid::query()
            ->where('project_manager_id', $request->user()->id)
            ->with('upworkAccount');

        return DataTableHelper::respond(
            $request,
            $query,
            ['job_title', 'status', 'bid_date'],
            function (Bid $bid) {
                return [
                    'id' => $bid->id,
                    'upwork_account' => $bid->upworkAccount->account_name ?? '—',
                    'upwork_account_id' => $bid->upwork_account_id,
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'upwork_account_id' => ['required', 'exists:upwork_accounts,id'],
            'job_title' => ['required', 'string', 'max:255'],
            'bid_date' => ['required', 'date'],
            'connects_used' => ['nullable', 'integer', 'min:0'],
            'proposal_amount' => ['nullable', 'numeric'],
            'client_budget' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['project_manager_id'] = $request->user()->id;
        $data['status'] = 'pending';

        $bid = Bid::create($data);

        return response()->json(['message' => 'Bid add ho gaya hai.', 'bid' => $bid]);
    }

    public function update(Request $request, Bid $bid)
    {
        abort_unless($bid->project_manager_id === $request->user()->id, 403);

        $data = $request->validate([
            'job_title' => ['required', 'string', 'max:255'],
            'bid_date' => ['required', 'date'],
            'connects_used' => ['nullable', 'integer', 'min:0'],
            'proposal_amount' => ['nullable', 'numeric'],
            'client_budget' => ['nullable', 'numeric'],
            'status' => ['required', Rule::in(['pending', 'won', 'lost', 'no_response'])],
            'notes' => ['nullable', 'string'],
        ]);

        $bid->update($data);

        return response()->json(['message' => 'Bid update ho gaya hai.']);
    }

    public function destroy(Request $request, Bid $bid)
    {
        abort_unless($bid->project_manager_id === $request->user()->id, 403);

        $bid->delete();

        return response()->json(['message' => 'Bid delete kar diya gaya hai.']);
    }
}
