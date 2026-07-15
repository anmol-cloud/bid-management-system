@props(['label', 'value', 'icon' => 'fa-chart-simple', 'accent' => 'amber'])
<div class="glass rounded-xl p-5 hover:shadow-glow transition">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs text-slate-500 font-medium mb-1">{{ $label }}</p>
            <p class="font-sora font-bold text-2xl text-white">{{ $value }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-glow/10 flex items-center justify-center text-amber-glow">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
    </div>
</div>
