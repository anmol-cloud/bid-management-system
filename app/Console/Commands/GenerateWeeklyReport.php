<?php

namespace App\Console\Commands;

use App\Mail\WeeklyReportMail;
use App\Models\Bid;
use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class GenerateWeeklyReport extends Command
{
    protected $signature = 'report:generate-weekly';

    protected $description = 'Last weeks bid performance report is generated and emailed to the admin.';

    public function handle(): int
    {
        $weekStart = Carbon::now()->subWeek()->startOfWeek();
        $weekEnd = Carbon::now()->subWeek()->endOfWeek();

        $bids = Bid::whereBetween('bid_date', [$weekStart, $weekEnd]);

        $total = (clone $bids)->count();
        $won = (clone $bids)->where('status', 'won')->count();
        $lost = (clone $bids)->where('status', 'lost')->count();

        $report = WeeklyReport::create([
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'total_bids' => $total,
            'won_bids' => $won,
            'lost_bids' => $lost,
            'success_rate' => $total > 0 ? round(($won / $total) * 100, 2) : 0,
        ]);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new WeeklyReportMail($report));
        }

        $report->update(['sent_to_admin' => true]);

        $this->info("The weekly report has been generated and sent to {$admins->count()} admin(s).");

        return self::SUCCESS;
    }
}
