<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bid Command Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sora: ['Sora','sans-serif'], inter: ['Inter','sans-serif'] }, colors: { amberglow: '#f5b942' } } } }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: radial-gradient(ellipse at top, #161a23 0%, #0a0c10 60%); }
        .glass { background: rgba(22,26,35,0.65); backdrop-filter: blur(14px); border: 1px solid rgba(245,185,66,0.12); }
    </style>
</head>
<body class="font-inter min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-xl bg-gradient-to-br from-amberglow to-amber-600 flex items-center justify-center shadow-[0_0_30px_rgba(245,185,66,0.25)] mb-4">
                <i class="fa-solid fa-bolt text-ink-900 text-xl" style="color:#0a0c10"></i>
            </div>
            <h1 class="font-sora font-extrabold text-2xl text-white">Bid Command Center</h1>
            <p class="text-slate-500 text-sm mt-1">Upwork bidding, tracked and managed in one place</p>
        </div>

        <div class="glass rounded-2xl p-8 shadow-2xl">
            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amberglow/40 focus:border-amberglow/50"
                           placeholder="you@company.com">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Password</label>
                    <input type="password" name="password" required
                           class="w-full bg-ink-800/60 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amberglow/40 focus:border-amberglow/50"
                           placeholder="••••••••">
                </div>
                <label class="flex items-center gap-2 text-xs text-slate-400">
                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-ink-800 text-amberglow focus:ring-amberglow/40">
                    Mujhe yaad rakho
                </label>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-amberglow to-amber-600 text-ink-900 font-sora font-bold py-2.5 rounded-lg hover:opacity-90 transition shadow-[0_0_20px_rgba(245,185,66,0.2)]">
                    Login
                </button>
            </form>
        </div>
        <p class="text-center text-slate-600 text-xs mt-6">&copy; {{ date('Y') }} Bid Command Center</p>
    </div>
</body>
</html>
