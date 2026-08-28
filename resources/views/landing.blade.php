<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid Command — Upwork Bidding & Team Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
                        amber: { glow: '#f5b942' },
                        ink: { 900: '#0a0c10', 800: '#10131a', 700: '#161a23', 600: '#1d222d' },
                    },
                    boxShadow: { glow: '0 0 24px rgba(245, 185, 66, 0.15)' },
                },
            },
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .blob {
            position: absolute; border-radius: 9999px; filter: blur(90px); opacity: .25;
            animation: float 12s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -40px) scale(1.1); }
        }
        .reveal { opacity: 0; transform: translateY(24px); transition: all .7s cubic-bezier(.2,.7,.3,1); }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .nav-link { position: relative; }
        .nav-link::after {
            content: ''; position: absolute; left: 0; bottom: -4px; width: 0; height: 2px;
            background: #f5b942; transition: width .25s ease;
        }
        .nav-link:hover::after { width: 100%; }
        #navbar { transition: background-color .3s ease, backdrop-filter .3s ease, box-shadow .3s ease, padding .3s ease; }
        .card-hover { transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease; }
        .card-hover:hover { transform: translateY(-6px); border-color: rgba(245,185,66,.4); box-shadow: 0 12px 40px rgba(245,185,66,.08); }
        .step-line { background: linear-gradient(180deg, rgba(245,185,66,.5), rgba(245,185,66,0)); }
    </style>
</head>
<body class="bg-ink-900 text-slate-300 antialiased overflow-x-hidden">

    <div class="blob w-96 h-96 bg-amber-glow -top-20 -left-20"></div>
    <div class="blob w-96 h-96 bg-amber-600 top-1/3 -right-32" style="animation-delay:3s"></div>
    <div class="blob w-72 h-72 bg-amber-glow bottom-0 left-1/4" style="animation-delay:6s"></div>

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 inset-x-0 z-50 px-6 lg:px-12 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-glow to-amber-600 flex items-center justify-center shadow-glow">
                    <i class="fa-solid fa-bolt text-ink-900 text-sm"></i>
                </div>
                <span class="font-sora font-bold text-white text-lg">Bid Command</span>
            </div>

            <div class="hidden md:flex items-center gap-8 font-sora text-sm text-slate-300">
                <a href="#features" class="nav-link hover:text-white">Features</a>
                <a href="#how-it-works" class="nav-link hover:text-white">How it Works</a>
                <a href="#benefits" class="nav-link hover:text-white">Who it's for</a>
            </div>

            <a href="{{ route('login') }}" class="bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold text-sm px-5 py-2.5 rounded-lg shadow-glow hover:scale-105 transition-transform">
                <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Login
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative pt-40 pb-28 px-6 lg:px-12">
        <div class="max-w-5xl mx-auto text-center reveal in">
            <span class="inline-block bg-white/5 border border-white/10 text-amber-glow text-xs font-sora font-semibold tracking-wide px-3.5 py-1.5 rounded-full mb-6">
                Upwork Bid Management, Simplified
            </span>
            <h1 class="font-sora font-extrabold text-4xl sm:text-5xl lg:text-6xl text-white leading-tight mb-6">
                One dashboard to manage <span class="text-amber-glow">every Upwork bid</span>,<br class="hidden sm:block"> every account, every manager.
            </h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto mb-10">
                Bid Command gives Admins, Sales Managers and Project Managers a single, role-based
                system to assign Upwork accounts, track bids, and get automatic weekly performance reports —
                without spreadsheets or WhatsApp updates.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold px-7 py-3.5 rounded-lg shadow-glow hover:scale-105 transition-transform">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Login to Dashboard
                </a>
                <a href="#how-it-works" class="border border-white/15 text-white font-sora font-semibold px-7 py-3.5 rounded-lg hover:bg-white/5 transition">
                    See how it works
                </a>
            </div>
        </div>
    </section>

    <!-- STAT CARDS -->
    <section id="features" class="relative px-6 lg:px-12 pb-24">
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-5">
            @php
                $stats = [
                    ['icon' => 'fa-user-shield', 'value' => '3', 'label' => 'Role Levels — Admin, Sales Manager, Project Manager'],
                    ['icon' => 'fa-gavel', 'value' => '100%', 'label' => 'Bids Tracked with Win/Loss Status'],
                    ['icon' => 'fa-file-lines', 'value' => 'Auto', 'label' => 'Weekly Reports Emailed to Admin'],
                    ['icon' => 'fa-bell', 'value' => 'Live', 'label' => 'In-App Notifications on Every Update'],
                ];
            @endphp
            @foreach ($stats as $i => $s)
                <div class="reveal bg-ink-800 border border-white/10 rounded-2xl p-5 card-hover" style="transition-delay: {{ $i * 80 }}ms">
                    <div class="w-11 h-11 rounded-xl bg-amber-glow/10 flex items-center justify-center mb-4">
                        <i class="fa-solid {{ $s['icon'] }} text-amber-glow"></i>
                    </div>
                    <p class="font-sora font-bold text-2xl text-white mb-1">{{ $s['value'] }}</p>
                    <p class="text-slate-400 text-sm leading-snug">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how-it-works" class="relative px-6 lg:px-12 py-24 bg-ink-800/60 border-y border-white/5">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16 reveal">
                <h2 class="font-sora font-bold text-3xl sm:text-4xl text-white mb-3">How to Use Bid Command</h2>
                <p class="text-slate-400 max-w-xl mx-auto">A simple, role-based flow — everyone only sees and does what's relevant to them.</p>
            </div>

            @php
                $steps = [
                    ['title' => 'Admin sets up the team', 'desc' => 'Admin creates Sales Manager and Project Manager accounts, and manages all users from one place.'],
                    ['title' => 'Upwork IDs get assigned', 'desc' => 'Admin or Sales Manager assigns Upwork accounts to a Sales Manager and Project Manager. IDs can be reassigned anytime.'],
                    ['title' => 'Sales Manager places bids', 'desc' => 'The Sales Manager submits bids on the assigned Upwork ID and tracks connects, proposal amount, and client budget.'],
                    ['title' => 'Project Manager tracks progress', 'desc' => "The PM views their assigned ID's bids and sees which ones are pending, won, or lost — read-only, no manual bidding."],
                    ['title' => 'Reports & notifications happen automatically', 'desc' => 'A weekly performance report is generated and emailed to Admin, plus everyone gets instant in-app notifications for assignments and results.'],
                ];
            @endphp

            <div class="space-y-10">
                @foreach ($steps as $i => $step)
                    <div class="reveal flex gap-5" style="transition-delay: {{ $i * 90 }}ms">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 shrink-0 rounded-full bg-gradient-to-br from-amber-glow to-amber-600 flex items-center justify-center text-ink-900 font-sora font-bold">
                                {{ $i + 1 }}
                            </div>
                            @if (!$loop->last)
                                <div class="w-px flex-1 step-line mt-2"></div>
                            @endif
                        </div>
                        <div class="pb-2">
                            <h3 class="font-sora font-semibold text-white text-lg mb-1.5">{{ $step['title'] }}</h3>
                            <p class="text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- WHO IT'S FOR / BENEFITS -->
    <section id="benefits" class="relative px-6 lg:px-12 py-24">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 reveal">
                <h2 class="font-sora font-bold text-3xl sm:text-4xl text-white mb-3">What is this helpful for?</h2>
                <p class="text-slate-400 max-w-xl mx-auto">Built for teams and agencies running Upwork bidding at scale.</p>
            </div>

            @php
                $benefits = [
                    ['icon' => 'fa-people-group', 'title' => 'Agencies managing multiple Upwork accounts', 'desc' => 'Keep every Upwork ID, its owner, and its assigned team in one organized place instead of scattered spreadsheets.'],
                    ['icon' => 'fa-sitemap', 'title' => 'Clear accountability across roles', 'desc' => 'Admin, Sales Manager and Project Manager each get exactly the access and view they need — no confusion over who does what.'],
                    ['icon' => 'fa-chart-line', 'title' => 'Real visibility into bid performance', 'desc' => 'Track success rate, won vs lost bids, and filter by manager, PM or account to see what is actually working.'],
                    ['icon' => 'fa-clock', 'title' => 'Less manual follow-up', 'desc' => 'Automatic weekly reports and instant notifications mean no one has to manually chase updates or compile numbers.'],
                ];
            @endphp

            <div class="grid sm:grid-cols-2 gap-6">
                @foreach ($benefits as $i => $b)
                    <div class="reveal bg-ink-800 border border-white/10 rounded-2xl p-6 card-hover" style="transition-delay: {{ $i * 90 }}ms">
                        <div class="w-11 h-11 rounded-xl bg-amber-glow/10 flex items-center justify-center mb-4">
                            <i class="fa-solid {{ $b['icon'] }} text-amber-glow"></i>
                        </div>
                        <h3 class="font-sora font-semibold text-white text-lg mb-2">{{ $b['title'] }}</h3>
                        <p class="text-slate-400 leading-relaxed">{{ $b['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="relative px-6 lg:px-12 pb-24">
        <div class="max-w-4xl mx-auto reveal bg-gradient-to-br from-amber-glow/10 to-transparent border border-amber-glow/20 rounded-3xl p-10 sm:p-14 text-center">
            <h2 class="font-sora font-bold text-3xl text-white mb-3">Ready to get your bidding organized?</h2>
            <p class="text-slate-400 mb-8">Login with your Admin, Sales Manager, or Project Manager account to get started.</p>
            <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-amber-glow to-amber-600 text-ink-900 font-sora font-semibold px-8 py-3.5 rounded-lg shadow-glow hover:scale-105 transition-transform">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Login to Dashboard
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-white/5 px-6 lg:px-12 py-8">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-slate-500 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-gradient-to-br from-amber-glow to-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-bolt text-ink-900 text-[10px]"></i>
                </div>
                <span class="font-sora font-semibold text-slate-300">Bid Command</span>
            </div>
            <p>&copy; {{ date('Y') }} Bid Command. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Navbar background on scroll
        const nav = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                nav.classList.add('bg-ink-900/80', 'backdrop-blur-md', 'shadow-lg', 'border-b', 'border-white/5');
            } else {
                nav.classList.remove('bg-ink-900/80', 'backdrop-blur-md', 'shadow-lg', 'border-b', 'border-white/5');
            }
        });

        // Reveal-on-scroll animation
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('in'); });
        }, { threshold: 0.15 });
        document.querySelectorAll('.reveal').forEach(el => io.observe(el));

        // Smooth scroll for nav links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelector(a.getAttribute('href'))?.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>