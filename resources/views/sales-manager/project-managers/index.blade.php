@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-sora font-semibold text-white text-lg">Project Managers</h2>
            <p class="text-slate-500 text-sm">Manage your team's project managers.</p>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold px-4 py-2.5 rounded-lg text-sm hover:opacity-90 transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Add PM
        </button>
    </div>

    <div class="glass rounded-xl p-4 sm:p-6 overflow-x-auto">
        <table id="pmTable" class="w-full text-sm">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div id="pmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4">
    <div class="glass rounded-2xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-5">
            <h3 id="pmModalTitle" class="font-sora font-semibold text-white text-lg">Add PM</h3>
            <button onclick="$('#pmModal').addClass('hidden').removeClass('flex')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="pmForm" class="space-y-3">
            <input type="hidden" id="pmId">
            <div><label class="block text-xs text-slate-400 mb-1">Full Name</label>
                <input type="text" id="pmName" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div><label class="block text-xs text-slate-400 mb-1">Email</label>
                <input type="email" id="pmEmail" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div><label class="block text-xs text-slate-400 mb-1">Phone</label>
                <input type="text" id="pmPhone" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <div id="pmStatusField" class="hidden"><label class="block text-xs text-slate-400 mb-1">Status</label>
                <select id="pmStatus" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                    <option value="active">Active</option><option value="inactive">Inactive</option>
                </select></div>
            <div><label class="block text-xs text-slate-400 mb-1">Password <span id="pmPassHint" class="text-slate-600"></span></label>
                <input type="password" id="pmPassword" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm"></div>
            <button type="submit" class="w-full bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold py-2.5 rounded-lg mt-2">Save</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let table;
$(function () {
    table = $('#pmTable').DataTable({
        processing: true, serverSide: true, ajax: '{{ route('sales-manager.pms.data') }}',
        columns: [
            { data: 'name' }, { data: 'email' }, { data: 'phone', defaultContent: '—' },
            { data: 'status', render: s => s === 'active'
                ? '<span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/10 text-emerald-400">Active</span>'
                : '<span class="px-2 py-0.5 rounded-full text-xs bg-red-500/10 text-red-400">Inactive</span>' },
            { data: 'created_at' },
            { data: null, orderable: false, render: (d,t,row) => `
                <button onclick='openEditModal(${JSON.stringify(row)})' class="text-amber-glow hover:text-amber-300 mr-3"><i class="fa-solid fa-pen"></i></button>
                <button onclick="deletePm(${row.id})" class="text-red-400 hover:text-red-300"><i class="fa-solid fa-trash"></i></button>
            ` },
        ],
    });
});
function openCreateModal() {
    $('#pmModalTitle').text('Add PM'); $('#pmForm')[0].reset(); $('#pmId').val('');
    $('#pmStatusField').addClass('hidden'); $('#pmPassHint').text('(required)');
    $('#pmModal').removeClass('hidden').addClass('flex');
}
function openEditModal(row) {
    $('#pmModalTitle').text('Edit PM'); $('#pmId').val(row.id); $('#pmName').val(row.name);
    $('#pmEmail').val(row.email); $('#pmPhone').val(row.phone); $('#pmStatus').val(row.status);
    $('#pmStatusField').removeClass('hidden'); $('#pmPassHint').text('(leave blank to keep same)');
    $('#pmModal').removeClass('hidden').addClass('flex');
}
$('#pmForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#pmId').val();
    const payload = { name: $('#pmName').val(), email: $('#pmEmail').val(), phone: $('#pmPhone').val(), status: $('#pmStatus').val() || 'active', password: $('#pmPassword').val() };
    const url = id ? `{{ url('sales-manager/project-managers') }}/${id}` : '{{ route('sales-manager.pms.store') }}';
    $.ajax({ url, method: id ? 'PUT' : 'POST', data: payload })
        .done(res => { toast(res.message); $('#pmModal').addClass('hidden').removeClass('flex'); table.ajax.reload(null, false); })
        .fail(ajaxError);
});
function deletePm(id) {
    confirmDelete(() => {
        $.ajax({ url: `{{ url('sales-manager/project-managers') }}/${id}`, method: 'DELETE' })
            .done(res => { toast(res.message); table.ajax.reload(null, false); }).fail(ajaxError);
    });
}
</script>
@endsection


