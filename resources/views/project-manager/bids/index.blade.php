@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-sora font-semibold text-white text-lg">My Bids</h2>
            <p class="text-slate-500 text-sm">Apne bids track karein aur status update karein</p>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold px-4 py-2.5 rounded-lg text-sm">
            <i class="fa-solid fa-plus mr-1.5"></i> Add Bid
        </button>
    </div>

    <div class="glass rounded-xl p-4 sm:p-6 overflow-x-auto">
        <table id="bidsTable" class="w-full text-sm">
            <thead><tr><th>Upwork Account</th><th>Job Title</th><th>Bid Date</th><th>Connects</th><th>Proposal</th><th>Status</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div id="bidModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4">
    <div class="glass rounded-2xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 id="bidModalTitle" class="font-sora font-semibold text-white text-lg">Add Bid</h3>
            <button onclick="$('#bidModal').addClass('hidden').removeClass('flex')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="bidForm" class="space-y-3">
            <input type="hidden" id="bidId">
            <div><label class="block text-xs text-slate-400 mb-1">Upwork Account</label>
                <select id="bid_account" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                    @foreach($myAccounts as $a)<option value="{{ $a->id }}">{{ $a->account_name }}</option>@endforeach
                </select></div>
            <div><label class="block text-xs text-slate-400 mb-1">Job Title</label>
                <input type="text" id="job_title" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs text-slate-400 mb-1">Bid Date</label>
                    <input type="date" id="bid_date" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
                <div><label class="block text-xs text-slate-400 mb-1">Connects Used</label>
                    <input type="number" id="connects_used" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs text-slate-400 mb-1">Proposal Amount</label>
                    <input type="number" step="0.01" id="proposal_amount" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
                <div><label class="block text-xs text-slate-400 mb-1">Client Budget</label>
                    <input type="number" step="0.01" id="client_budget" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            </div>
            <div id="bidStatusField" class="hidden"><label class="block text-xs text-slate-400 mb-1">Status</label>
                <select id="bid_status" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                    <option value="pending">Pending</option><option value="won">Won</option><option value="lost">Lost</option><option value="no_response">No Response</option>
                </select></div>
            <div><label class="block text-xs text-slate-400 mb-1">Notes</label>
                <textarea id="bid_notes" rows="2" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></textarea></div>
            <button type="submit" class="w-full bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold py-2.5 rounded-lg mt-2">Save</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let table;
const statusBadge = s => ({
    pending: '<span class="px-2 py-0.5 rounded-full text-xs bg-amber-500/10 text-amber-glow">Pending</span>',
    won: '<span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/10 text-emerald-400">Won</span>',
    lost: '<span class="px-2 py-0.5 rounded-full text-xs bg-red-500/10 text-red-400">Lost</span>',
    no_response: '<span class="px-2 py-0.5 rounded-full text-xs bg-slate-500/10 text-slate-400">No Response</span>',
}[s]);

$(function () {
    table = $('#bidsTable').DataTable({
        processing: true, serverSide: true, ajax: '{{ route('project-manager.bids.data') }}',
        columns: [
            { data: 'upwork_account' }, { data: 'job_title' }, { data: 'bid_date' }, { data: 'connects_used' },
            { data: 'proposal_amount', render: v => v ? '$'+v : '—' },
            { data: 'status', render: statusBadge },
            { data: null, orderable: false, render: (d,t,row) => `
                <button onclick='openEditModal(${JSON.stringify(row)})' class="text-amber-glow hover:text-amber-300 mr-3"><i class="fa-solid fa-pen"></i></button>
                <button onclick="deleteBid(${row.id})" class="text-red-400 hover:text-red-300"><i class="fa-solid fa-trash"></i></button>
            ` },
        ],
    });
});
function openCreateModal() {
    $('#bidModalTitle').text('Add Bid'); $('#bidForm')[0].reset(); $('#bidId').val('');
    $('#bidStatusField').addClass('hidden');
    $('#bidModal').removeClass('hidden').addClass('flex');
}
function openEditModal(row) {
    $('#bidModalTitle').text('Edit Bid'); $('#bidId').val(row.id);
    $('#bid_account').val(row.upwork_account_id); $('#job_title').val(row.job_title);
    $('#connects_used').val(row.connects_used); $('#proposal_amount').val(row.proposal_amount);
    $('#client_budget').val(row.client_budget); $('#bid_status').val(row.status);
    $('#bidStatusField').removeClass('hidden');
    $('#bidModal').removeClass('hidden').addClass('flex');
}
$('#bidForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#bidId').val();
    const payload = {
        upwork_account_id: $('#bid_account').val(), job_title: $('#job_title').val(), bid_date: $('#bid_date').val(),
        connects_used: $('#connects_used').val(), proposal_amount: $('#proposal_amount').val(),
        client_budget: $('#client_budget').val(), status: $('#bid_status').val() || 'pending', notes: $('#bid_notes').val(),
    };
    const url = id ? `{{ url('project-manager/bids') }}/${id}` : '{{ route('project-manager.bids.store') }}';
    $.ajax({ url, method: id ? 'PUT' : 'POST', data: payload })
        .done(res => { toast(res.message); $('#bidModal').addClass('hidden').removeClass('flex'); table.ajax.reload(null, false); })
        .fail(ajaxError);
});
function deleteBid(id) {
    confirmDelete(() => {
        $.ajax({ url: `{{ url('project-manager/bids') }}/${id}`, method: 'DELETE' })
            .done(res => { toast(res.message); table.ajax.reload(null, false); }).fail(ajaxError);
    });
}
</script>
@endsection
