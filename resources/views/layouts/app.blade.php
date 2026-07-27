<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Bid Command Center' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sora: ['Sora', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        amber: {
                            glow: '#f5b942',
                        },
                        ink: {
                            900: '#0a0c10',
                            800: '#10131a',
                            700: '#161a23',
                            600: '#1d222d',
                        },
                    },
                    boxShadow: {
                        glow: '0 0 24px rgba(245, 185, 66, 0.15)',
                    },
                },
            },
        }
    </script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/jquery.dataTables.min.css">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body { background: radial-gradient(ellipse at top, #161a23 0%, #0a0c10 60%); }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb { background: #f5b94255; border-radius: 8px; }
        .glass { background: rgba(22, 26, 35, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(245,185,66,0.08); }
        table.dataTable { color: #cbd3e1 !important; background: transparent !important; }
        table.dataTable thead th { color: #f5b942 !important; border-bottom: 1px solid rgba(245,185,66,0.2) !important; font-family: 'Sora', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: .05em; }
        table.dataTable tbody td { border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
        table.dataTable tbody tr:hover { background: rgba(245,185,66,0.05) !important; }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select { background: #161a23; border: 1px solid rgba(245,185,66,0.2); color: #fff; border-radius: 6px; padding: 4px 8px; }
        .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { color: #8b93a7 !important; }
        .paginate_button { color: #cbd3e1 !important; }
        .paginate_button.current { background: #f5b942 !important; color: #0a0c10 !important; border-radius: 6px; }
    </style>
</head>
<body class="font-inter text-slate-200 min-h-screen">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
    {{-- Sidebar --}}
    <aside class="fixed lg:static z-40 inset-y-0 left-0 w-64 glass border-r border-white/5 transform lg:translate-x-0 transition-transform duration-200 -translate-x-full"
           id="sidebar">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/5">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-glow to-amber-600 flex items-center justify-center shadow-glow">
                <i class="fa-solid fa-bolt text-ink-900 text-sm"></i>
            </div>
            <div>
                <p class="font-sora font-bold text-white text-sm leading-tight">Bid Command</p>
                <p class="text-[11px] text-slate-500">Center</p>
            </div>
        </div>

        <nav class="px-3 py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-amber-glow/10 text-amber-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} transition">
                <i class="fa-solid fa-gauge-high w-4"></i> Dashboard
            </a>

            @auth
            @if(auth()->user()->isAdmin())
                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-wider text-slate-600 font-semibold">Admin</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-amber-glow/10 text-amber-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} transition">
                    <i class="fa-solid fa-users-gear w-4"></i> User Management
                </a>
                <a href="{{ route('admin.assignments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.assignments.*') ? 'bg-amber-glow/10 text-amber-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} transition">
                    <i class="fa-solid fa-diagram-project w-4"></i> Assignments
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-amber-glow/10 text-amber-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} transition">
                    <i class="fa-solid fa-chart-line w-4"></i> Reports & Analytics
                </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isSalesManager())
                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-wider text-slate-600 font-semibold">Sales Manager</p>
                <a href="{{ route('sales-manager.pms.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('sales-manager.pms.*') ? 'bg-amber-glow/10 text-amber-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} transition">
                    <i class="fa-solid fa-user-tie w-4"></i> Project Managers
                </a>
                <a href="{{ route('sales-manager.upwork.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('sales-manager.upwork.*') ? 'bg-amber-glow/10 text-amber-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} transition">
                    <i class="fa-solid fa-id-card w-4"></i> Upwork IDs
                </a>
            @endif

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-wider text-slate-600 font-semibold">Bidding</p>
            <a href="{{ route('project-manager.bids.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('project-manager.bids.*') ? 'bg-amber-glow/10 text-amber-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} transition">
                <i class="fa-solid fa-gavel w-4"></i> My Bids
            </a>
            @endauth
        </nav>

        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition text-sm">
                    <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="fixed inset-0 bg-black/60 z-30 lg:hidden hidden" id="sidebarOverlay"></div>

    {{-- Main --}}
    <div class="flex-1 min-w-0">
        <header class="glass border-b border-white/5 px-4 sm:px-6 py-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button id="sidebarToggle" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="font-sora font-semibold text-white text-lg">{{ $heading ?? 'Dashboard' }}</h1>
            </div>
            <div class="flex items-center gap-4">
                <button id="notifBtn" class="relative text-slate-400 hover:text-amber-glow transition">
                    <i class="fa-regular fa-bell text-lg"></i>
                    <span id="notifBadge" class="hidden absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 items-center justify-center"></span>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-glow to-amber-600 flex items-center justify-center text-ink-900 font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <p class="text-sm text-white font-medium">{{ auth()->user()->name ?? '' }}</p>
                        <p class="text-[11px] text-slate-500 capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? '') }}</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    });
    document.getElementById('sidebarOverlay')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
    });

    function toast(message, icon = 'success') {
        Swal.fire({ toast: true, position: 'top-end', icon, title: message, showConfirmButton: false, timer: 2500, background: '#161a23', color: '#e2e8f0' });
    }

    function confirmDelete(callback) {
        Swal.fire({
            title: 'Do you really want to delete it?',
            text: 'This action cannot be undone..',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'yes, go ahead ',
            cancelButtonText: 'Cancel',
            background: '#161a23', color: '#e2e8f0',
            confirmButtonColor: '#ef4444',
        }).then((result) => { if (result.isConfirmed) callback(); });
    }

    function ajaxError(xhr) {
        const msg = xhr.responseJSON?.message || 'Something went wrong; please try again.';
        toast(msg, 'error');
    }

    // Poll unread notification count
    function refreshNotifCount() {
        $.get('{{ route('notifications.unread-count') }}', function (res) {
            const badge = $('#notifBadge');
            if (res.count > 0) {
                badge.removeClass('hidden').addClass('flex').text(res.count > 9 ? '9+' : res.count);
            } else {
                badge.addClass('hidden');
            }
        });
    }
    @auth
    refreshNotifCount();
    setInterval(refreshNotifCount, 30000);
    document.getElementById('notifBtn')?.addEventListener('click', () => window.location.href = '{{ route('notifications.index') }}');
    @endauth
</script>

@yield('scripts')
</body>
</html>
