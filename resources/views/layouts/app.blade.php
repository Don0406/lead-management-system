<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LeadBridge | Crafting Connections')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FDFCFB; color: #5A4651; overflow-x: hidden; }
        .brand-font { font-family: 'Playfair Display', serif; }
        
        .grain-overlay { position: relative; }
        .grain-overlay::before {
            content: ""; position: absolute; inset: 0; width: 100%; height: 100%;
            opacity: 0.04; pointer-events: none; z-index: 50;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }
        .pattern-bg {
            background-image: radial-gradient(rgba(174, 161, 129, 0.15) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .smokey-bg { position: relative; background: #5A384B; overflow: hidden; }
        .smoke-wrap { position: absolute; inset: 0; pointer-events: none; }
        .smoke-wave {
            position: absolute; bottom: -20px; left: 0; width: 200%; height: 120%;
            background-repeat: repeat-x; background-position: bottom;
            animation: moveWave 30s linear infinite alternate;
        }
        .wave-1 {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1600 400'%3E%3Cpath fill='%23AEA181' fill-opacity='0.15' d='M0,160 C400,320 1200,0 1600,160 L1600,400 L0,400 Z'%3E%3C/path%3E%3C/svg%3E");
        }
        @keyframes moveWave {
            from { transform: translateX(0); }
            to { transform: translateX(-30%); }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FDFCFB; }
        ::-webkit-scrollbar-thumb { background: #AEA181; }
    </style>
</head>
<body class="flex flex-col min-h-screen grain-overlay" x-data="{ mobileMenu: false }">

{{-- ALERTS --}}
<div class="fixed top-32 right-8 z-[200] space-y-4 pointer-events-none">
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="w-80 bg-white border-l-4 border-[#AEA181] shadow-2xl p-6 pointer-events-auto">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 text-[#AEA181]"><i class="fas fa-check-circle"></i></div>
            <div class="flex-1">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#5A4651] mb-1">Protocol Activated</h3>
                <p class="text-[11px] text-slate-400 italic font-light leading-relaxed">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-slate-300 hover:text-[#5A4651]"><i class="fas fa-times text-[10px]"></i></button>
        </div>
    </div>
    @endif

    @if(session('error') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" class="w-80 bg-white border-l-4 border-red-500 shadow-2xl p-6 pointer-events-auto">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 text-red-500"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="flex-1">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#5A4651] mb-1">System Breach</h3>
                <p class="text-[11px] text-slate-400 italic font-light leading-relaxed">
                    {{ session('error') ?? 'Validation parameters not met.' }}
                </p>
            </div>
            <button @click="show = false" class="text-slate-300 hover:text-[#5A4651]"><i class="fas fa-times text-[10px]"></i></button>
        </div>
    </div>
    @endif
</div>

{{-- NAVIGATION --}}
<nav class="smokey-bg py-6 px-6 md:px-12 shadow-2xl sticky top-0 z-[100]">
    <div class="smoke-wrap"><div class="smoke-wave wave-1"></div></div>
    
    <div class="relative z-10 max-w-[1600px] mx-auto grid grid-cols-3 items-center">
        
        <div class="flex items-center gap-6 lg:gap-8">
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-[#AEA181] hover:text-white transition-colors">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <a href="/" class="group relative hidden sm:block">
                <span class="text-[9px] font-black uppercase tracking-[0.7em] text-[#AEA181]">HOME</span>
                <div class="absolute -bottom-2 left-0 w-full h-[1px] bg-[#AEA181] scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            </a>

            @auth
                <div class="hidden lg:flex items-center gap-6 border-l border-white/10 pl-6">
                    <a href="{{ route('dashboard') }}" class="text-[9px] font-black uppercase tracking-[0.5em] {{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/40 hover:text-white' }} transition-all">Overview</a>
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isRep())
                        <a href="{{ route('leads.index') }}" class="text-[9px] font-black uppercase tracking-[0.5em] {{ request()->routeIs('leads.*') ? 'text-white' : 'text-white/40 hover:text-white' }} transition-all">Ledger</a>
                        <a href="{{ route('terminal.index') }}" class="text-[9px] font-black uppercase tracking-[0.5em] {{ request()->routeIs('terminal.*') ? 'text-white' : 'text-white/40 hover:text-white' }} transition-all">Terminal</a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.provision') }}" class="text-[8px] font-black uppercase tracking-[0.3em] {{ request()->routeIs('admin.provision') ? 'text-[#AEA181]' : 'text-[#AEA181]/50 hover:text-[#AEA181]' }} transition-all border border-[#AEA181]/30 px-3 py-1 bg-white/5 rounded-sm">Provisioning</a>
                    @endif
                </div>
            @endauth
        </div>

        <div class="text-center">
            <a href="/">
                <h1 class="brand-font text-2xl md:text-3xl italic text-white tracking-tighter leading-none">LeadBridge</h1>
                <p class="text-[7px] font-bold tracking-[0.6em] text-[#AEA181] uppercase mt-1 hidden md:block">Architecture of Growth</p>
            </a>
        </div>

        <div class="flex items-center justify-end gap-6">
            @auth
                <div class="flex items-center gap-4 border-l border-white/10 pl-6">
                    <div class="hidden xl:block text-right">
                        <p class="text-[9px] font-black uppercase tracking-widest text-white">{{ Auth::user()->name }}</p>
                        <p class="text-[7px] text-[#AEA181] italic tracking-widest uppercase">{{ Auth::user()->role }} Auth</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="relative group">
                        <div class="w-9 h-9 rounded-full overflow-hidden border border-white/20 group-hover:border-[#AEA181] transition-all duration-500">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-[#AEA181]/20 flex items-center justify-center text-[#AEA181] text-[10px] font-black">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="hidden md:block ml-2">
                        @csrf
                        <button type="submit" class="text-[9px] font-black uppercase tracking-[0.3em] text-white/20 hover:text-red-400 transition-colors">
                            LOG OUT
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-[9px] font-black uppercase tracking-[0.3em] text-[#AEA181] hover:text-white transition-colors">
                    Access Portal
                </a>
            @endauth
        </div>
    </div>

    {{-- MOBILE MENU OVERLAY --}}
    <div x-show="mobileMenu" x-transition class="fixed inset-0 z-[150] lg:hidden">
        <div class="absolute inset-0 bg-[#261E23]/95 backdrop-blur-md" @click="mobileMenu = false"></div>
        <div class="absolute inset-y-0 left-0 w-3/4 bg-[#5A384B] p-12 space-y-12">
            <h2 class="brand-font text-3xl italic text-white">Menu.</h2>
            <div class="flex flex-col gap-8">
                <a href="/" class="text-xs font-black uppercase tracking-[0.5em] text-[#AEA181]">Home</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-xs font-black uppercase tracking-[0.5em] text-white">Overview</a>
                    <a href="{{ route('leads.index') }}" class="text-xs font-black uppercase tracking-[0.5em] text-white">The Ledger</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.provision') }}" class="text-xs font-black uppercase tracking-[0.5em] text-[#AEA181]">Provisioning</a>
                    @endif
                    <hr class="border-white/10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-black uppercase tracking-[0.5em] text-red-400">Log Out</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="flex-grow pattern-bg py-12">
    <div class="max-w-[1600px] mx-auto px-6 md:px-12">
        @yield('content')
    </div>
</main>

<footer class="bg-[#261E23] pt-24 pb-12 px-12 border-t border-white/5 relative z-10 overflow-hidden">
    <div class="max-w-[1600px] mx-auto text-center md:text-left">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-24">
            <div class="space-y-6">
                <div class="brand-font text-3xl text-white italic tracking-tighter">LeadBridge</div>
                <p class="text-[10px] tracking-[0.2em] text-white/30 uppercase leading-relaxed">
                    Precision lead management for the modern enterprise.
                </p>
            </div>
            </div>
        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-[9px] tracking-[0.4em] text-white/10 uppercase">
                &copy; {{ date('Y') }} LeadBridge LMS — Engineering Momentum
            </div>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 1200, once: true, offset: 50, easing: 'ease-in-out-cubic' });
    });
</script>
</body>
</html>