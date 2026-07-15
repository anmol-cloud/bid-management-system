@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-sora font-semibold text-white text-lg">User Management</h2>
            <p class="text-slate-500 text-sm">Sales Managers aur Project Managers ko manage karein</p>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold px-4 py-2.5 rounded-lg text-sm hover:opacity-90 transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Add User
        </button>
    </div>

    <div class="glass rounded-xl p-4 sm:p-6 overflow-x-auto">
        <table id="usersTable" class="w-full text-sm">
            <thead>
                <tr>
                    <th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div id="userModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4">
    <div class="glass rounded-2xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-5">
            <h3 id="modalTitle" class="font-sora font-semibold text-white text-lg">Add User</h3>
            <button onclick="closeModal()" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="userForm" class="space-y-3">
            <input type="hidden" id="userId">
            <div>
                <label class="block text-xs text-slate-400 mb-1">Full Name</label>
                <input type="text" id="name" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-glow/40">
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Email</label>
                <input type="email" id="email" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-glow/40">
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Phone</label>
                <input type="text" id="phone" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-glow/40">
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Role</label>
                <select id="role" required class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-glow/40">
                    <option value="sales_manager">Sales Manager</option>
                    <option value="project_manager">Project Manager</option>
                </select>
            </div>
            <div id="statusField" class="hidden">
                <label class="block text-xs text-slate-400 mb-1">Status</label>
                <select id="status" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-glow/40">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Password <span id="passHint" class="text-slate-600"></span></label>
                <input type="password" id="password" class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-glow/40">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold py-2.5 rounded-lg mt-2 hover:opacity-90 transition">
                Save
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let table;
$(function () {
    table = $('#usersTable').DataTable({
        processing: true, serverSide: true,
        ajax: '{{ route('admin.users.data') }}',
        columns: [
            { data: 'name' }, { data: 'email' }, { data: 'phone', defaultContent: '—' },
            { data: 'role', render: r => `<span class="capitalize">${r.replace('_',' ')}</span>` },
            { data: 'status', render: s => s === 'active'
                ? '<span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/10 text-emerald-400">Active</span>'
                : '<span class="px-2 py-0.5 rounded-full text-xs bg-red-500/10 text-red-400">Inactive</span>' },
            { data: 'created_at' },
            { data: null, orderable: false, render: (d, t, row) => `
                <button onclick='openEditModal(${JSON.stringify(row)})' class="text-amber-glow hover:text-amber-300 mr-3"><i class="fa-solid fa-pen"></i></button>
                <button onclick="deleteUser(${row.id})" class="text-red-400 hover:text-red-300"><i class="fa-solid fa-trash"></i></button>
            ` },
        ],
    });
});

function openCreateModal() {
    $('#modalTitle').text('Add User'); $('#userForm')[0].reset();
    $('#userId').val(''); $('#statusField').addClass('hidden'); $('#passHint').text('(required)');
    $('#userModal').removeClass('hidden').addClass('flex');
}
function openEditModal(row) {
    $('#modalTitle').text('Edit User');
    $('#userId').val(row.id); $('#name').val(row.name); $('#email').val(row.email);
    $('#phone').val(row.phone); $('#role').val(row.role); $('#status').val(row.status);
    $('#statusField').removeClass('hidden'); $('#passHint').text('(leave blank to keep same)');
    $('#userModal').removeClass('hidden').addClass('flex');
}
function closeModal() { $('#userModal').addClass('hidden').removeClass('flex'); }

$('#userForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#userId').val();
    const payload = {
        name: $('#name').val(), email: $('#email').val(), phone: $('#phone').val(),
        role: $('#role').val(), status: $('#status').val() || 'active', password: $('#password').val(),
    };
    const url = id ? `{{ url('admin/users') }}/${id}` : '{{ route('admin.users.store') }}';
    const method = id ? 'PUT' : 'POST';
    $.ajax({ url, method, data: payload })
        .done(res => { toast(res.message); closeModal(); table.ajax.reload(null, false); })
        .fail(ajaxError);
});

function deleteUser(id) {
    confirmDelete(() => {
        $.ajax({ url: `{{ url('admin/users') }}/${id}`, method: 'DELETE' })
            .done(res => { toast(res.message); table.ajax.reload(null, false); })
            .fail(ajaxError);
    });
}
</script>
@endsection
