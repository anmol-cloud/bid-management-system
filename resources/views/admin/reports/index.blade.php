@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div>
        <h2 class="font-sora font-semibold text-white text-lg">Reports & Analytics</h2>
        <p class="text-slate-500 text-sm">View performance by applying filters, or export the data.</p>
    </div>

    <div class="glass rounded-xl p-5 grid grid-cols-1 sm:grid-cols-4 gap-3">
        <select id="f_sm" class="bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <option value="">All Sales Managers</option>
            @foreach($salesManagers as $sm)<option value="{{ $sm->id }}">{{ $sm->name }}</option>@endforeach
        </select>
        <select id="f_pm" class="bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <option value="">All Project Managers</option>
            @foreach($projectManagers as $pm)<option value="{{ $pm->id }}">{{ $pm->name }}</option>@endforeach
        </select>
        <select id="f_account" class="bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <option value="">All Upwork IDs</option>
            @foreach($upworkAccounts as $a)<option value="{{ $a->id }}">{{ $a->account_name }}</option>@endforeach
        </select>
        <button onclick="loadAnalytics()" class="bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold px-4 py-2 rounded-lg text-sm">
            <i class="fa-solid fa-filter mr-1.5"></i> Apply
        </button>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="analyticsCards">
        <x-stat-card label="Total Bids" value="—" icon="fa-gavel" />
        <x-stat-card label="Won" value="—" icon="fa-trophy" />
        <x-stat-card label="Lost" value="—" icon="fa-circle-xmark" />
        <x-stat-card label="Success Rate" value="—" icon="fa-bullseye" />
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.reports.excel') }}" class="glass px-4 py-2.5 rounded-lg text-sm text-slate-300 hover:text-amber-glow transition">
            <i class="fa-solid fa-file-csv mr-1.5"></i> Export CSV
        </a>
    </div>

    <div class="glass rounded-xl p-4 sm:p-6">
        <h3 class="font-sora font-semibold text-white mb-4">Weekly Auto-Generated Reports</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-amber-glow text-xs uppercase border-b border-white/10">
                    <th class="py-2">Week</th><th>Total Bids</th><th>Won</th><th>Lost</th><th>Success Rate</th><th>Sent to Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $r)
                <tr class="border-b border-white/5">
                    <td class="py-2">{{ $r->week_start->format('d M') }} - {{ $r->week_end->format('d M Y') }}</td>
                    <td>{{ $r->total_bids }}</td>
                    <td>{{ $r->won_bids }}</td>
                    <td>{{ $r->lost_bids }}</td>
                    <td>{{ $r->success_rate }}%</td>
                    <td>{{ $r->sent_to_admin ? '✅' : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-6 text-center text-slate-500">No weekly report has been generated yet..</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $reports->links() }}</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function loadAnalytics() {
    $.get('{{ route('admin.reports.analytics') }}', {
        sales_manager_id: $('#f_sm').val(), project_manager_id: $('#f_pm').val(), upwork_account_id: $('#f_account').val(),
    }, function (res) {
        const cards = $('#analyticsCards > div');
        $(cards[0]).find('p.font-bold').text(res.total);
        $(cards[1]).find('p.font-bold').text(res.won);
        $(cards[2]).find('p.font-bold').text(res.lost);
        $(cards[3]).find('p.font-bold').text(res.success_rate + '%');
    });
}
loadAnalytics();
</script>
@endsection
