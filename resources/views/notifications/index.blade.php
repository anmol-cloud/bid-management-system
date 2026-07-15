@extends('layouts.app')
@section('content')
<div class="space-y-4 max-w-2xl">
    <h2 class="font-sora font-semibold text-white text-lg">Notifications</h2>

    @forelse($notifications as $n)
    <div class="glass rounded-xl p-4 flex items-start gap-3 {{ $n->read_at ? 'opacity-60' : '' }}">
        <div class="w-9 h-9 rounded-lg bg-amber-glow/10 flex items-center justify-center text-amber-glow shrink-0">
            <i class="fa-solid fa-bell"></i>
        </div>
        <div class="flex-1">
            <p class="text-white text-sm font-medium">{{ $n->data['title'] ?? 'Notification' }}</p>
            <p class="text-slate-400 text-sm mt-0.5">{{ $n->data['message'] ?? '' }}</p>
            <p class="text-slate-600 text-xs mt-1">{{ $n->created_at->diffForHumans() }}</p>
        </div>
        @unless($n->read_at)
        <button onclick="markRead('{{ $n->id }}', this)" class="text-xs text-amber-glow hover:underline whitespace-nowrap">Mark read</button>
        @endunless
    </div>
    @empty
    <div class="glass rounded-xl p-8 text-center text-slate-500">Koi notification abhi tak nahi hai.</div>
    @endforelse

    <div>{{ $notifications->links() }}</div>
</div>
@endsection

@section('scripts')
<script>
function markRead(id, btn) {
    $.post(`/notifications/${id}/read`).done(() => {
        $(btn).closest('.glass').addClass('opacity-60');
        $(btn).remove();
    });
}
</script>
@endsection
