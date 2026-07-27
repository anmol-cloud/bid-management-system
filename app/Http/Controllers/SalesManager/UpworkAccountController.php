<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Models\UpworkAccount;
use App\Support\DataTableHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpworkAccountController extends Controller
{
    public function index()
    {
        return view('sales-manager.upwork-accounts.index');
    }

    public function data(Request $request)
    {
        $userId = $request->user()->id;
        $query = UpworkAccount::query()->where(function ($q) use ($userId) {
            $q->where('created_by', $userId)
                ->orWhereHas('assignments', function ($a) use ($userId) {
                    $a->where('is_active', true)->where('sales_manager_id', $userId);
                });
        });

        return DataTableHelper::respond(
            $request,
            $query,
            ['account_name', 'upwork_id', 'status'],
            function (UpworkAccount $a) {
                return [
                    'id' => $a->id,
                    'account_name' => $a->account_name,
                    'upwork_id' => $a->upwork_id,
                    'hourly_rate' => $a->hourly_rate,
                    'connects_available' => $a->connects_available,
                    'status' => $a->status,
                    'created_at' => $a->created_at->format('d M Y'),
                ];
            }
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'upwork_id' => ['required', 'string', 'unique:upwork_accounts,upwork_id'],
            'account_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'profile_url' => ['nullable', 'url'],
            'hourly_rate' => ['nullable', 'numeric'],
            'connects_available' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['created_by'] = $request->user()->id;
        $data['status'] = 'active';

        $account = UpworkAccount::create($data);

        return response()->json(['message' => 'The Upwork account has been added.', 'account' => $account]);
    }

    public function update(Request $request, UpworkAccount $upworkAccount)
    {
        $data = $request->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'profile_url' => ['nullable', 'url'],
            'hourly_rate' => ['nullable', 'numeric'],
            'connects_available' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(['active', 'suspended', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ]);

        $upworkAccount->update($data);

        return response()->json(['message' => 'The Upwork account has been updated.']);
    }

    public function destroy(UpworkAccount $upworkAccount)
    {
        $upworkAccount->delete();

        return response()->json(['message' => 'The Upwork account has been deleted.']);
    }
}
