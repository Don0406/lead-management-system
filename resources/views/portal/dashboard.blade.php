@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-10" data-aos="fade-up">
    
    {{-- 1. CLIENT NAVIGATION HUB --}}
    <div class="flex flex-wrap gap-8 mb-12 border-b border-slate-100 pb-2">
        <a href="{{ route('dashboard') }}" class="text-[10px] font-black uppercase tracking-[0.3em] {{ request()->routeIs('dashboard') ? 'text-[#5A4651] border-b-2 border-[#AEA181]' : 'text-slate-300' }} pb-6">
            Project Overview
        </a>
        
        {{-- Updated Link for Orders --}}
        <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-[#5A4651] transition-all pb-6">
            My Orders
        </a>
        
        <a href="{{ route('terminal.index') }}" class="relative text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-[#5A4651] transition-all pb-6">
            Mail Terminal
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="absolute -top-1 -right-4 flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#AEA181] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#AEA181]"></span>
                </span>
            @endif
        </a>

        {{-- Updated Link for Security --}}
        <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-[#5A4651] transition-all pb-6">
            Security Profile
        </a>
    </div>

    {{-- 2. WELCOME HEADER --}}
    <div class="text-center space-y-4 mb-12">
        <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block">Secure Environment</span>
        <h2 class="brand-font text-5xl text-[#5A4651] italic leading-tight">
            Welcome, {{ explode(' ', auth()->user()->name)[0] }}<span class="text-[#AEA181]">.</span>
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- LEFT COLUMN: STATUS & DETAILS --}}
        <div class="lg:col-span-2 space-y-10">
            
            {{-- Progress Tracking Card --}}
            <div class="bg-white p-12 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[3px] bg-[#AEA181]"></div>
                
                <div class="mb-12 flex justify-between items-end">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">Current Project Phase</p>
                        <h3 class="text-3xl font-light text-[#5A4651] uppercase tracking-tighter">
                            {{ ucfirst($lead->status ?? 'Initial Inquiry') }}
                        </h3>
                    </div>
                    <span class="text-[10px] font-bold text-[#AEA181] italic">Updated {{ $lead ? $lead->updated_at->diffForHumans() : 'Recently' }}</span>
                </div>

                <div class="relative pt-8">
                    <div class="flex justify-between mb-4 relative z-10">
                        @php
                            $stages = ['new', 'contacted', 'qualified', 'proposal', 'closed_won'];
                            $currentStatus = strtolower($lead->status ?? 'new');
                        @endphp
                        @foreach($stages as $stage)
                            <div class="text-center">
                                <div class="w-4 h-4 rounded-full mx-auto border-2 transition-all duration-700 
                                    {{ $currentStatus == $stage ? 'bg-[#AEA181] border-[#AEA181] shadow-[0_0_15px_rgba(174,161,129,0.5)]' : 'bg-white border-slate-100' }}">
                                </div>
                                <p class="text-[8px] uppercase tracking-widest mt-4 font-black {{ $currentStatus == $stage ? 'text-[#5A4651]' : 'text-slate-200' }}">
                                    {{ str_replace('_', ' ', $stage) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="absolute top-[40px] left-0 w-full h-[1px] bg-slate-100 z-0"></div>
                </div>
            </div>

            {{-- Project Dossier --}}
            <div class="bg-white border border-slate-100 p-12 relative">
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#5A4651] mb-10 border-b border-slate-50 pb-4">Project Dossier</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-20">
                    <div>
                        <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block mb-2">Primary Organization</label>
                        <p class="text-sm italic text-[#5A4651] font-medium border-b border-slate-50 pb-2">{{ $lead->company ?? 'Principal Individual' }}</p>
                    </div>
                    <div>
                        <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block mb-2">Registered Email</label>
                        <p class="text-sm italic text-[#5A4651] font-medium border-b border-slate-50 pb-2">{{ $lead->email ?? auth()->user()->email }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block mb-2">Current Requirements Brief</label>
                        <p class="text-sm italic text-[#5A4651] leading-relaxed bg-[#FDFCFB] p-6 border border-slate-50">
                            {{ $lead->notes ?? 'Our executive team is currently synthesizing your requirements into a formal project scope.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- NEW: CURATED ADDITIONS (Product Catalog) --}}
            <div class="bg-white border border-slate-100 p-12 relative">
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#5A4651] mb-10 border-b border-slate-50 pb-4">Curated Additions</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Product Item 1 --}}
                    <div class="group border border-slate-50 p-6 hover:border-[#AEA181]/30 transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h5 class="text-[11px] font-black uppercase tracking-widest text-[#5A4651]">Premium Interior Pack</h5>
                                <p class="text-[9px] text-[#AEA181] font-bold mt-1">Acquisition Phase Available</p>
                            </div>
                            <span class="text-[10px] font-light text-slate-400">$12,500</span>
                        </div>
                        <p class="text-[10px] italic text-slate-400 leading-relaxed mb-6">
                            Complete interior architectural styling tailored to your specific project dossier.
                        </p>
                        <button class="w-full py-3 bg-[#5A4651] text-white text-[8px] font-black uppercase tracking-[0.2em] group-hover:bg-[#AEA181] transition-all">
                            Initialize Order
                        </button>
                    </div>

                    {{-- Product Item 2 --}}
                    <div class="group border border-slate-50 p-6 hover:border-[#AEA181]/30 transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h5 class="text-[11px] font-black uppercase tracking-widest text-[#5A4651]">Site Analysis Pro</h5>
                                <p class="text-[9px] text-[#AEA181] font-bold mt-1">Pre-Project Ready</p>
                            </div>
                            <span class="text-[10px] font-light text-slate-400">$4,200</span>
                        </div>
                        <p class="text-[10px] italic text-slate-400 leading-relaxed mb-6">
                            Deep-dive topographical and environmental reporting for your registered site.
                        </p>
                        <button class="w-full py-3 bg-[#5A4651] text-white text-[8px] font-black uppercase tracking-[0.2em] group-hover:bg-[#AEA181] transition-all">
                            Initialize Order
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: SPECIALIST & MAIL --}}
        <div class="space-y-8">
            
            {{-- Specialist Card --}}
            <div class="bg-[#5A4651] p-10 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-[-20px] right-[-20px] opacity-5 text-7xl italic brand-font text-white">LB</div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[#AEA181] mb-8">Executive Liaison</p>
                    @if(isset($representative) && $representative)
                        <h4 class="brand-font text-2xl italic mb-2">{{ $representative->name }}</h4>
                        <p class="text-[10px] uppercase tracking-widest text-[#AEA181] font-bold">Project Specialist</p>
                        <div class="pt-8 mt-8 border-t border-white/10">
                            <a href="{{ route('terminal.index') }}" class="group flex items-center gap-4 text-[9px] font-black uppercase tracking-[0.4em] text-[#AEA181] hover:text-white transition-all">
                                Secure Message 
                                <span class="w-6 h-[1px] bg-[#AEA181] group-hover:w-12 transition-all"></span>
                            </a>
                        </div>
                    @else
                        <h4 class="brand-font text-2xl italic mb-2">Pending Liaison</h4>
                        <p class="text-[10px] opacity-50 leading-relaxed">We are currently assigning a specialist to manage your account requirements.</p>
                    @endif
                </div>
            </div>

            {{-- Mail Terminal Preview --}}
            <div class="bg-white border border-slate-100 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-[#5A4651]">Mail Terminal</h4>
                    <span class="w-2 h-2 rounded-full bg-[#AEA181] {{ (isset($unreadCount) && $unreadCount > 0) ? 'animate-pulse' : 'opacity-20' }}"></span>
                </div>
                
                <div class="space-y-4">
                    @php
                        $lastMsg = \App\Models\Message::where('receiver_id', auth()->id())
                                    ->with('sender')
                                    ->latest()
                                    ->first();
                    @endphp

                    @if($lastMsg)
                        <div class="p-4 bg-[#FDFCFB] border border-slate-50 transition-hover hover:border-[#AEA181]/30 cursor-pointer">
                            <p class="text-[10px] font-bold text-[#5A4651] mb-1">
                                {{ $lastMsg->sender->name ?? 'System' }}
                            </p>
                            <p class="text-[11px] italic text-slate-400 line-clamp-2">
                                "{{ $lastMsg->message }}"
                            </p>
                        </div>
                    @else
                        <div class="p-4 bg-[#FDFCFB] border border-slate-50 text-center">
                            <p class="text-[10px] text-slate-300 uppercase tracking-widest">No active dispatches</p>
                        </div>
                    @endif
                </div>

                <a href="{{ route('terminal.index') }}" class="mt-6 block w-full py-4 text-center text-[8px] font-black uppercase tracking-[0.3em] text-slate-400 border border-slate-100 hover:border-[#5A4651] hover:text-[#5A4651] transition-all">
                    Access Full Terminal
                </a>
            </div>

        </div>
    </div>
</div>
@endsection