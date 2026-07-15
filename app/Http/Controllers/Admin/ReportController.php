<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\UpworkAccount;
use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = WeeklyReport::orderByDesc('week_start')->paginate(15);
        $salesManagers = User::where('role', 'sales_manager')->orderBy('name')->get();
        $projectManagers = User::where('role', 'project_manager')->orderBy('name')->get();
        $upworkAccounts = UpworkAccount::orderBy('account_name')->get();

        return view('admin.reports.index', compact('reports', 'salesManagers', 'projectManagers', 'upworkAccounts'));
    }

    public function analytics(Request $request)
    {
        $query = Bid::query();

        if ($request->filled('sales_manager_id')) {
            $query->whereHas('upworkAccount.activeAssignment', function ($q) use ($request) {
                $q->where('sales_manager_id', $request->input('sales_manager_id'));
            });
        }

        if ($request->filled('project_manager_id')) {
            $query->where('project_manager_id', $request->input('project_manager_id'));
        }

        if ($request->filled('upwork_account_id')) {
            $query->where('upwork_account_id', $request->input('upwork_account_id'));
        }

        if ($request->filled('from')) {
            $query->where('bid_date', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->where('bid_date', '<=', $request->input('to'));
        }

        $total = (clone $query)->count();
        $won = (clone $query)->where('status', 'won')->count();
        $lost = (clone $query)->where('status', 'lost')->count();
        $pending = (clone $query)->where('status', 'pending')->count();

        return response()->json([
            'total' => $total,
            'won' => $won,
            'lost' => $lost,
            'pending' => $pending,
            'success_rate' => $total > 0 ? round(($won / $total) * 100, 1) : 0,
        ]);
    }

    public function exportPdf(WeeklyReport $report)
    {
        // Requires: composer require barryvdh/laravel-dompdf
        $pdf = app('dompdf.wrapper')->loadView('reports.pdf', compact('report'));

        return $pdf->download("weekly-report-{$report->week_start->format('Y-m-d')}.pdf");
    }

    public function exportExcel(Request $request)
    {
        // Requires: composer require maatwebsite/excel
        $from = $request->input('from');
        $to = $request->input('to');

        $bids = Bid::when($from, fn ($q) => $q->where('bid_date', '>=', $from))
            ->when($to, fn ($q) => $q->where('bid_date', '<=', $to))
            ->with(['upworkAccount', 'projectManager'])
            ->get();

        return response()->streamDownload(function () use ($bids) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Upwork ID', 'PM', 'Job Title', 'Bid Date', 'Status', 'Proposal Amount']);
            foreach ($bids as $bid) {
                fputcsv($out, [
                    $bid->upworkAccount->upwork_id ?? '',
                    $bid->projectManager->name ?? '',
                    $bid->job_title,
                    $bid->bid_date->format('Y-m-d'),
                    $bid->status,
                    $bid->proposal_amount,
                ]);
            }
            fclose($out);
        }, 'bid-report.csv');
    }
}
