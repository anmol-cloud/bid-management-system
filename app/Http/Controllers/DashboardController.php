<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\UpworkAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isSalesManager()) {
            return $this->salesManagerDashboard($user);
        }

        return $this->projectManagerDashboard($user);
    }

    protected function adminDashboard()
    {
        $stats = [
            'total_upwork_accounts' => UpworkAccount::count(),
            'active_upwork_accounts' => UpworkAccount::where('status', 'active')->count(),
            'total_sales_managers' => User::where('role', 'sales_manager')->count(),
            'total_project_managers' => User::where('role', 'project_manager')->count(),
            'total_bids' => Bid::count(),
            'won_bids' => Bid::won()->count(),
            'lost_bids' => Bid::lost()->count(),
        ];

        $stats['success_rate'] = $stats['total_bids'] > 0
            ? round(($stats['won_bids'] / $stats['total_bids']) * 100, 1)
            : 0;

        $weeklyTrend = $this->weeklyBidTrend(Bid::query());

        return view('dashboard.admin', compact('stats', 'weeklyTrend'));
    }

    protected function salesManagerDashboard(User $user)
    {
        $accountIds = UpworkAccount::visibleTo($user)->pluck('id');

        $stats = [
            'my_upwork_accounts' => $accountIds->count(),
            'my_project_managers' => User::where('role', 'project_manager')->where('created_by', $user->id)->count(),
            'total_bids' => Bid::whereIn('upwork_account_id', $accountIds)->count(),
            'won_bids' => Bid::whereIn('upwork_account_id', $accountIds)->won()->count(),
            'lost_bids' => Bid::whereIn('upwork_account_id', $accountIds)->lost()->count(),
        ];

        $stats['success_rate'] = $stats['total_bids'] > 0
            ? round(($stats['won_bids'] / $stats['total_bids']) * 100, 1)
            : 0;

        $weeklyTrend = $this->weeklyBidTrend(Bid::whereIn('upwork_account_id', $accountIds));

        return view('dashboard.sales-manager', compact('stats', 'weeklyTrend'));
    }

    protected function projectManagerDashboard(User $user)
    {
        $accountIds = UpworkAccount::visibleTo($user)->pluck('id');

        $stats = [
            'assigned_accounts' => $accountIds->count(),
            'total_bids' => Bid::where('project_manager_id', $user->id)->count(),
            'won_bids' => Bid::where('project_manager_id', $user->id)->won()->count(),
            'lost_bids' => Bid::where('project_manager_id', $user->id)->lost()->count(),
        ];

        $stats['success_rate'] = $stats['total_bids'] > 0
            ? round(($stats['won_bids'] / $stats['total_bids']) * 100, 1)
            : 0;

        $weeklyTrend = $this->weeklyBidTrend(Bid::where('project_manager_id', $user->id));

        return view('dashboard.project-manager', compact('stats', 'weeklyTrend'));
    }

    protected function weeklyBidTrend($query)
    {
        $since = Carbon::now()->subWeeks(6)->startOfWeek();

        return (clone $query)
            ->where('bid_date', '>=', $since)
            ->selectRaw('YEARWEEK(bid_date, 1) as yw, MIN(bid_date) as week_start, COUNT(*) as total, SUM(status = "won") as won')
            ->groupBy('yw')
            ->orderBy('yw')
            ->get();
    }
}
