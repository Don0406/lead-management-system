@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center px-6" data-aos="fade-up">
    <div class="w-full max-w-lg">
        
        {{-- Architectural Login Card --}}
        <div class="bg-white p-10 md:p-16 shadow-[0_30px_60px_-15px_rgba(90,56,75,0.1)] border border-slate-100 relative overflow-hidden">
            {{-- Gold Accent Top --}}
            <div class="absolute top-0 left-0 w-full h-[3px] bg-[#AEA181]"></div>
            
            <div class="text-center mb-12">
                <span class="text-[9px] font-black uppercase tracking-[0.9em] text-[#AEA181] block mb-4">LeadBridge Protocol</span>
                <h2 class="brand-font text-4xl text-[#5A4651] italic leading-none">Access Portal</h2>
                <div class="w-12 h-[1px] bg-[#5A384B]/20 mx-auto mt-6"></div>
            </div>

            {{-- Validation Alerts --}}
            @if($errors->any())
                <div class="mb-8 p-4 bg-red-50 border-l-2 border-red-400">
                    @foreach($errors->all() as $error)
                        <p class="text-[10px] uppercase tracking-widest text-red-600 italic font-bold mb-1">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-10">
                @csrf

                {{-- Email Field --}}
                <div class="space-y-3">
                    <label for="email" class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.3em] opacity-60">System Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-4 text-sm italic placeholder-slate-200 bg-transparent transition-colors">
                </div>

                {{-- Password Field --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.3em] opacity-60">Security Key</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[8px] uppercase tracking-[0.2em] text-[#AEA181] hover:text-[#5A384B] transition-colors">Forgotten?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required
                           class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-4 text-sm italic placeholder-slate-200 bg-transparent transition-colors">
                </div>

                {{-- Remember & Sign In --}}
                <div class="pt-4 flex flex-col gap-6">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="remember" id="remember" class="rounded-none border-slate-200 text-[#5A384B] focus:ring-[#AEA181]">
                        <label for="remember" class="text-[9px] uppercase tracking-[0.3em] text-slate-400">Maintain Session</label>
                    </div>

                    <button type="submit" class="w-full py-6 bg-[#5A384B] text-white text-[11px] font-black uppercase tracking-[0.5em] hover:bg-[#5A4651] transition-all shadow-xl group flex items-center justify-center gap-4">
                        Initialize Access
                        <span class="w-8 h-[1px] bg-[#AEA181] group-hover:w-16 transition-all duration-500"></span>
                    </button>
                </div>
            </form>

            {{-- Guest/Register Section --}}
            @if(Route::has('register'))
                <div class="mt-16 pt-10 border-t border-slate-50 text-center">
                    <p class="text-[9px] uppercase tracking-[0.4em] text-slate-400 mb-6 font-light italic">New Partner / Guest Account?</p>
                    <a href="{{ route('register') }}" class="inline-block px-10 py-4 border border-[#AEA181]/30 text-[9px] font-black uppercase tracking-[0.3em] text-[#5A4651] hover:bg-[#AEA181] hover:text-white transition-all">
                        Request System Entry
                    </a>
                </div>
            @endif

            {{-- Demo Terminal (Discreet) --}}
            <div class="mt-12 p-6 bg-[#FDFCFB] border border-slate-50">
                <p class="text-[8px] font-black uppercase tracking-[0.4em] text-[#AEA181] mb-4 text-center">Protocol Sandbox (Demo)</p>
                <div class="grid grid-cols-1 gap-2 text-[10px] font-light italic text-slate-400">
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span>Architect (Admin):</span>
                        <span class="text-[#5A4651]">admin@leadbridge.com</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span>Management:</span>
                        <span class="text-[#5A4651]">manager@leadbridge.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Representative:</span>
                        <span class="text-[#5A4651]">john@leadbridge.com</span>
                    </div>
                </div>
                <p class="text-center text-[8px] text-slate-300 uppercase tracking-[0.2em] mt-4">Key: password</p>
            </div>
        </div>
        
        {{-- Back to Landing --}}
        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="text-[9px] font-black uppercase tracking-[0.6em] text-slate-300 hover:text-[#AEA181] transition-colors">
                ← Return to Zenith
            </a>
        </div>
    </div>
</div>
@endsection