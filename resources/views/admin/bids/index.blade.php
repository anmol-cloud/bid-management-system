@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-sora font-semibold text-white text-lg">All Bids</h2>
            <p class="text-slate-500 text-sm">Bids submitted by all project managers, across all Upwork accounts.</p>
        </div>
        <div class="flex items-center gap-2">
            <select id="filterPm" class="bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                <option value="">All Project Managers</option>
                @foreach($projectManagers as $pm)
                    <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                @endforeach
            </select>
            <select id="filterStatus" class="bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="won">Won</option>
                <option value="lost">Lost</option>
                <option value="no_response">No Response</option>
            </select>
        </div>
    </div>

    <div class="glass rounded-xl p-4 sm:p-6 overflow-x-auto">
        <table id="allBidsTable" class="w-full text-sm">
            <thead>
                <tr><th>Project Manager</th><th>Upwork Account</th><th>Job Title</th><th>Bid Date</th><th>Connects</th><th>Proposal</th><th>Client Budget</th><th>Status</th></tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
const statusBadge = s => ({
    pending: '<span class="px-2 py-0.5 rounded-full text-xs bg-amber-500/10 text-amber-glow">Pending</span>',
    won: '<span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/10 text-emerald-400">Won</span>',
    lost: '<span class="px-2 py-0.5 rounded-full text-xs bg-red-500/10 text-red-400">Lost</span>',
    no_response: '<span class="px-2 py-0.5 rounded-full text-xs bg-slate-500/10 text-slate-400">No Response</span>',
}[s]);

let table;
$(function () {
    table = $('#allBidsTable').DataTable({
        processing: true, serverSide: true,
        ajax: {
            url: '{{ route('admin.bids.data') }}',
            data: function (d) {
                d.status = $('#filterStatus').val();
                d.project_manager_id = $('#filterPm').val();
            }
        },
        columns: [
            { data: 'project_manager' }, { data: 'upwork_account' }, { data: 'job_title' }, { data: 'bid_date' },
            { data: 'connects_used' },
            { data: 'proposal_amount', render: v => v ? '$'+v : '—' },
            { data: 'client_budget', render: v => v ? '$'+v : '—' },
            { data: 'status', render: statusBadge },
        ],
    });
});

$('#filterStatus, #filterPm').on('change', function () {
    table.ajax.reload();
});
</script>
@endsection