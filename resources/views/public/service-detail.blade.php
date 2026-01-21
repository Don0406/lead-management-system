@extends('layouts.app')

@section('content')
<div class="bg-[#FDFCFB] min-h-screen font-sans antialiased selection:bg-[#AEA181] selection:text-white">
    
    {{-- 1. HEADER / BREADCRUMB --}}
    <div class="max-w-6xl mx-auto pt-24 pb-12 px-8" data-aos="fade-down">
        <a href="{{ url('/') }}" class="group inline-flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.5em] text-slate-400 hover:text-[#5A384B] transition-all no-underline">
            <span class="w-8 h-[1px] bg-slate-200 group-hover:w-12 group-hover:bg-[#AEA181] transition-all"></span>
            Return to Portfolio
        </a>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <section class="max-w-6xl mx-auto px-8 pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-24">
            
            {{-- LEFT: INFORMATION --}}
            <div class="lg:col-span-7 space-y-12" data-aos="fade-right">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-6">Capability Detail // {{ $service['n'] }}</span>
                    <h1 class="brand-font text-6xl md:text-8xl text-[#5A4651] italic leading-[1.1] mb-8">
                        {{ $service['title'] }}
                    </h1>
                    <p class="text-xl font-light text-[#5A384B] opacity-70 italic border-l-4 border-[#AEA181] ps-8 leading-relaxed">
                        {{ $service['tagline'] }}
                    </p>
                </div>

                <div class="pt-12 border-t border-slate-100">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#5A4651] mb-8">Operational Scope</h4>
                    <p class="text-md leading-relaxed text-slate-500 font-light italic">
                        {{ $service['what_it_does'] }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <div class="bg-white p-10 border border-slate-100 shadow-sm">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#AEA181] mb-8">Key Provisions</h4>
                        <ul class="space-y-5">
                            @foreach($service['what_it_offers'] as $offer)
                                <li class="flex items-start gap-4 text-[12px] text-slate-500 italic leading-snug">
                                    <span class="text-[#AEA181] font-bold">/</span>
                                    {{ $offer }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- Visual decorative element --}}
                    <div class="hidden md:flex items-center justify-center border border-dashed border-slate-200 opacity-40">
                        <span class="text-[10px] uppercase tracking-[1em] rotate-90 text-slate-300">Architecture</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: REGISTRATION FORM --}}
            <div class="lg:col-span-5" data-aos="fade-left">
                <div class="sticky top-12 bg-[#5A4651] p-10 md:p-12 text-white shadow-2xl overflow-hidden relative">
                    {{-- Decorative background --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#AEA181] opacity-10 translate-x-16 -translate-y-16 rotate-45"></div>

                    <div class="relative z-10">
                        <span class="text-[9px] font-black uppercase tracking-[0.5em] text-[#AEA181] block mb-4 text-center">Protocol Activation</span>
                        <h3 class="brand-font text-3xl italic text-center mb-10">Inquire for {{ $service['title'] }}</h3>

                        {{-- Reuse your landing store route --}}
                        <form action="{{ route('landing.store') }}" method="POST" class="space-y-8">
                            @csrf
                            {{-- This hidden input tells the system which product they clicked --}}
                            <input type="hidden" name="interest" value="{{ $service['title'] }}">

                            <div class="space-y-2">
                                <label class="text-[9px] uppercase font-black tracking-widest opacity-60">Principal Name</label>
                                <input type="text" name="name" required class="w-full bg-transparent border-0 border-b border-white/20 focus:border-[#AEA181] focus:ring-0 py-3 italic text-sm placeholder-white/10" placeholder="Full Name">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] uppercase font-black tracking-widest opacity-60">Business Email</label>
                                <input type="email" name="email" required class="w-full bg-transparent border-0 border-b border-white/20 focus:border-[#AEA181] focus:ring-0 py-3 italic text-sm placeholder-white/10" placeholder="email@company.com">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] uppercase font-black tracking-widest opacity-60">Organization</label>
                                <input type="text" name="company" class="w-full bg-transparent border-0 border-b border-white/20 focus:border-[#AEA181] focus:ring-0 py-3 italic text-sm placeholder-white/10" placeholder="Business Name">
                            </div>

                            <div class="pt-8">
                                <button type="submit" class="w-full py-6 bg-[#AEA181] text-white text-[11px] font-black uppercase tracking-[0.4em] hover:bg-white hover:text-[#5A4651] transition-all shadow-xl group flex items-center justify-center gap-4">
                                    Initialize Configuration
                                    <span class="w-8 h-[1px] bg-white group-hover:bg-[#5A4651] transition-all"></span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="mt-8 text-[9px] text-center opacity-40 italic tracking-widest">
                            Secure enterprise-grade data handling active.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- 3. FOOTER NAV --}}
    <div class="py-20 text-center border-t border-slate-50">
        <a href="{{ url('/') }}#book-demo" class="text-[9px] font-black uppercase tracking-[0.5em] text-slate-400 hover:text-[#5A384B] transition-all">
            General Inquiries →
        </a>
    </div>
</div>
@endsection