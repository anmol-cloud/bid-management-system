@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card label="My Upwork Accounts" :value="$stats['my_upwork_accounts']" icon="fa-id-card" />
        <x-stat-card label="My Project Managers" :value="$stats['my_project_managers']" icon="fa-users" />
        <x-stat-card label="Total Bids" :value="$stats['total_bids']" icon="fa-gavel" />
        <x-stat-card label="Success Rate" :value="$stats['success_rate'].'%'" icon="fa-bullseye" />
    </div>

    <div class="glass rounded-xl p-6">
        <h3 class="font-sora font-semibold text-white mb-4">Weekly Bid Trend</h3>
        <canvas id="trendChart" height="90"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const trend = @json($weeklyTrend);
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trend.map(t => t.week_start),
            datasets: [
                { label: 'Total Bids', data: trend.map(t => t.total), borderColor: '#f5b942', backgroundColor: 'rgba(245,185,66,0.1)', tension: 0.35, fill: true },
                { label: 'Won', data: trend.map(t => t.won), borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.08)', tension: 0.35, fill: true },
            ]
        },
        options: {
            plugins: { legend: { labels: { color: '#cbd3e1' } } },
            scales: {
                x: { ticks: { color: '#8b93a7' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y: { ticks: { color: '#8b93a7' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            }
        }
    });
</script>
@endsection
