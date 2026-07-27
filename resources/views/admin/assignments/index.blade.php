@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div>
        <h2 class="font-sora font-semibold text-white text-lg">Account Assignments</h2>
        <p class="text-slate-500 text-sm">Assign/reassign Upwork leads to sales managers and project managers.</p>
    </div>

    <div class="glass rounded-xl p-4 sm:p-6 overflow-x-auto">
        <table id="assignTable" class="w-full text-sm">
            <thead>
                <tr><th>Upwork ID</th><th>Account Name</th><th>Sales Manager</th><th>Project Manager</th><th>Status</th><th>Actions</th></tr>
            </thead>
        </table>
    </div>
</div>

<div id="assignModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4">
    <div class="glass rounded-2xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-sora font-semibold text-white text-lg">Assign Upwork ID</h3>
            <button onclick="$('#assignModal').addClass('hidden').removeClass('flex')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="assignForm" class="space-y-3">
            <input type="hidden" id="assign_account_id">
            <div>
                <label class="block text-xs text-slate-400 mb-1">Sales Manager</label>
                <select id="assign_sm" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                    <option value="">— None —</option>
                    @foreach($salesManagers as $sm)
                        <option value="{{ $sm->id }}">{{ $sm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Project Manager</label>
                <select id="assign_pm" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                    <option value="">— None —</option>
                    @foreach($projectManagers as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold py-2.5 rounded-lg mt-2 hover:opacity-90 transition">Save Assignment</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let table;
$(function () {
    table = $('#assignTable').DataTable({
        processing: true, serverSide: true,
        ajax: '{{ route('admin.assignments.data') }}',
        columns: [
            { data: 'upwork_id' }, { data: 'account_name' }, { data: 'sales_manager' }, { data: 'project_manager' },
            { data: 'status', render: s => s === 'active'
                ? '<span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/10 text-emerald-400">Active</span>'
                : '<span class="px-2 py-0.5 rounded-full text-xs bg-slate-500/10 text-slate-400">'+s+'</span>' },
            { data: null, orderable: false, render: (d,t,row) => `<button onclick='openAssign(${JSON.stringify(row)})' class="text-amber-glow hover:text-amber-300"><i class="fa-solid fa-arrows-turn-right mr-1"></i>Assign</button>` },
        ],
    });
});
function openAssign(row) {
    $('#assign_account_id').val(row.id);
    $('#assign_sm').val(row.sales_manager_id || '');
    $('#assign_pm').val(row.project_manager_id || '');
    $('#assignModal').removeClass('hidden').addClass('flex');
}
$('#assignForm').on('submit', function (e) {
    e.preventDefault();
    $.post('{{ route('admin.assignments.store') }}', {
        upwork_account_id: $('#assign_account_id').val(),
        sales_manager_id: $('#assign_sm').val() || null,
        project_manager_id: $('#assign_pm').val() || null,
    }).done(res => { toast(res.message); $('#assignModal').addClass('hidden').removeClass('flex'); table.ajax.reload(null, false); })
      .fail(ajaxError);
});
</script>
@endsection
