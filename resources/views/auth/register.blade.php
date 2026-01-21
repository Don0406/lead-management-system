@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col items-center justify-center px-6" data-aos="fade-up">
    <div class="w-full max-w-lg">
        
        {{-- Architectural Registration Card --}}
        <div class="bg-white p-10 md:p-16 shadow-[0_30px_60px_-15px_rgba(90,56,75,0.1)] border border-slate-100 relative overflow-hidden">
            {{-- Gold Accent Top --}}
            <div class="absolute top-0 left-0 w-full h-[3px] bg-[#AEA181]"></div>
            
            <div class="text-center mb-12">
                <span class="text-[9px] font-black uppercase tracking-[0.9em] text-[#AEA181] block mb-4">Onboarding Protocol</span>
                <h2 class="brand-font text-4xl text-[#5A4651] italic leading-none">Request Credentials</h2>
                <div class="w-12 h-[1px] bg-[#5A384B]/20 mx-auto mt-6"></div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                {{-- Full Name --}}
                <div class="space-y-2">
                    <label for="name" class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.3em] opacity-60">Principal Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-3 text-sm italic placeholder-slate-200 bg-transparent transition-colors @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-[10px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                </div>

                {{-- Email Address --}}
                <div class="space-y-2">
                    <label for="email" class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.3em] opacity-60">Professional Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-3 text-sm italic placeholder-slate-200 bg-transparent transition-colors @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-[10px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.3em] opacity-60">Define Security Key</label>
                    <input id="password" type="password" name="password" required
                           class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-3 text-sm italic placeholder-slate-200 bg-transparent transition-colors @error('password') border-red-400 @enderror">
                    @error('password') <p class="text-[10px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="space-y-2">
                    <label for="password-confirm" class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.3em] opacity-60">Verify Security Key</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required
                           class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-3 text-sm italic placeholder-slate-200 bg-transparent transition-colors">
                </div>

                {{-- Submit Registration --}}
                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-[#5A384B] text-white text-[11px] font-black uppercase tracking-[0.5em] hover:bg-[#5A4651] transition-all shadow-xl group flex items-center justify-center gap-4">
                        Generate Account
                        <span class="w-8 h-[1px] bg-[#AEA181] group-hover:w-16 transition-all duration-500"></span>
                    </button>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-slate-50 text-center">
                <p class="text-[9px] uppercase tracking-[0.4em] text-slate-400 mb-4 font-light italic">Already have credentials?</p>
                <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-[#AEA181] hover:text-[#5A384B] transition-colors">
                    Access Portal Entry
                </a>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="text-[9px] font-black uppercase tracking-[0.6em] text-slate-300 hover:text-[#AEA181] transition-colors">
                ← Return to Zenith
            </a>
        </div>
    </div>
</div>
@endsection