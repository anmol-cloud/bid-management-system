@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-sora font-semibold text-white text-lg">Upwork IDs</h2>
            <p class="text-slate-500 text-sm">Add Upwork accounts and assign them to project managers.</p>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold px-4 py-2.5 rounded-lg text-sm">
            <i class="fa-solid fa-plus mr-1.5"></i> Add Upwork ID
        </button>
    </div>

    <div class="glass rounded-xl p-4 sm:p-6 overflow-x-auto">
        <table id="accTable" class="w-full text-sm">
            <thead><tr><th>Account Name</th><th>Upwork ID</th><th>Rate</th><th>Connects</th><th>Status</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div id="accModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4">
    <div class="glass rounded-2xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 id="accModalTitle" class="font-sora font-semibold text-white text-lg">Add Upwork ID</h3>
            <button onclick="$('#accModal').addClass('hidden').removeClass('flex')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="accForm" class="space-y-3">
            <input type="hidden" id="accId">
            <div><label class="block text-xs text-slate-400 mb-1">Upwork ID</label>
                <input type="text" id="upwork_id" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div><label class="block text-xs text-slate-400 mb-1">Account Name</label>
                <input type="text" id="account_name" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div><label class="block text-xs text-slate-400 mb-1">Email</label>
                <input type="email" id="acc_email" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div><label class="block text-xs text-slate-400 mb-1">Profile URL</label>
                <input type="url" id="profile_url" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs text-slate-400 mb-1">Hourly Rate</label>
                    <input type="number" step="0.01" id="hourly_rate" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
                <div><label class="block text-xs text-slate-400 mb-1">Connects</label>
                    <input type="number" id="connects_available" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            </div>
            <div id="accStatusField" class="hidden"><label class="block text-xs text-slate-400 mb-1">Status</label>
                <select id="acc_status" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                    <option value="active">Active</option><option value="suspended">Suspended</option><option value="inactive">Inactive</option>
                </select></div>
            <div><label class="block text-xs text-slate-400 mb-1">Notes</label>
                <textarea id="notes" rows="2" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></textarea></div>
            <button type="submit" class="w-full bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold py-2.5 rounded-lg mt-2">Save</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let table;
$(function () {
    table = $('#accTable').DataTable({
        processing: true, serverSide: true, ajax: '{{ route('sales-manager.upwork.data') }}',
        columns: [
            { data: 'account_name' }, { data: 'upwork_id' },
            { data: 'hourly_rate', render: v => v ? '$'+v : '—' },
            { data: 'connects_available' },
            { data: 'status', render: s => s === 'active'
                ? '<span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/10 text-emerald-400">Active</span>'
                : '<span class="px-2 py-0.5 rounded-full text-xs bg-slate-500/10 text-slate-400">'+s+'</span>' },
            { data: null, orderable: false, render: (d,t,row) => `
                <button onclick='openEditModal(${JSON.stringify(row)})' class="text-amber-glow hover:text-amber-300 mr-3"><i class="fa-solid fa-pen"></i></button>
                <button onclick="deleteAcc(${row.id})" class="text-red-400 hover:text-red-300"><i class="fa-solid fa-trash"></i></button>
            ` },
        ],
    });
});
function openCreateModal() {
    $('#accModalTitle').text('Add Upwork ID'); $('#accForm')[0].reset(); $('#accId').val('');
    $('#upwork_id').prop('disabled', false); $('#accStatusField').addClass('hidden');
    $('#accModal').removeClass('hidden').addClass('flex');
}
function openEditModal(row) {
    $('#accModalTitle').text('Edit Upwork ID'); $('#accId').val(row.id);
    $('#upwork_id').val(row.upwork_id).prop('disabled', true);
    $('#account_name').val(row.account_name); $('#hourly_rate').val(row.hourly_rate);
    $('#connects_available').val(row.connects_available); $('#acc_status').val(row.status);
    $('#accStatusField').removeClass('hidden');
    $('#accModal').removeClass('hidden').addClass('flex');
}
$('#accForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#accId').val();
    const payload = {
        upwork_id: $('#upwork_id').val(), account_name: $('#account_name').val(), email: $('#acc_email').val(),
        profile_url: $('#profile_url').val(), hourly_rate: $('#hourly_rate').val(),
        connects_available: $('#connects_available').val(), status: $('#acc_status').val() || 'active', notes: $('#notes').val(),
    };
    const url = id ? `{{ url('sales-manager/upwork-accounts') }}/${id}` : '{{ route('sales-manager.upwork.store') }}';
    $.ajax({ url, method: id ? 'PUT' : 'POST', data: payload })
        .done(res => { toast(res.message); $('#accModal').addClass('hidden').removeClass('flex'); table.ajax.reload(null, false); })
        .fail(ajaxError);
});
function deleteAcc(id) {
    confirmDelete(() => {
        $.ajax({ url: `{{ url('sales-manager/upwork-accounts') }}/${id}`, method: 'DELETE' })
            .done(res => { toast(res.message); table.ajax.reload(null, false); }).fail(ajaxError);
    });
}
</script>
@endsection
