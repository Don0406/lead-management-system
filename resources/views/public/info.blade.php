@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-24 px-6" data-aos="fade-up">
    {{-- Header Section --}}
    <div class="mb-16 border-b border-slate-200 pb-10">
        <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-4">LeadBridge Governance</span>
        <h1 class="brand-font text-6xl text-[#5A4651] italic mb-4">{{ $title }}<span class="text-[#AEA181]">.</span></h1>
        <div class="flex items-center gap-4">
            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400 font-bold italic">Operational Protocol v1.0</p>
            <span class="h-[1px] w-12 bg-[#AEA181]/30"></span>
            <p class="text-[11px] uppercase tracking-[0.3em] text-[#AEA181] font-bold">Status: Active</p>
        </div>
    </div>

    {{-- Content Document --}}
    <div class="bg-white border border-slate-100 p-12 md:p-20 shadow-2xl relative overflow-hidden ring-1 ring-black/5">
        {{-- Aesthetic LB Watermark --}}
        <div class="absolute -right-16 -bottom-16 opacity-[0.03] pointer-events-none select-none">
            <h2 class="brand-font text-[15rem] italic uppercase">LB</h2>
        </div>

        <div class="prose prose-slate max-w-none relative z-10">
            <div class="text-slate-600 leading-[1.8] space-y-6 text-lg">
                {!! $content !!}
            </div>
        </div>
    </div>

    {{-- Return Action --}}
    <div class="mt-16 flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="text-center md:text-left">
            <p class="text-[9px] font-mono text-slate-400 uppercase tracking-widest italic">Verification ID: {{ strtoupper(substr(md5($title), 0, 12)) }}</p>
            <p class="text-[9px] text-slate-300 uppercase tracking-widest mt-1">LeadBridge Management System &copy; {{ date('Y') }}</p>
        </div>
        <a href="/" class="group flex items-center gap-4 text-[11px] font-black uppercase tracking-[0.3em] text-[#AEA181] hover:text-[#5A384B] transition-all">
            <span class="h-[1px] w-8 bg-[#AEA181] group-hover:w-12 transition-all"></span>
            Return to Zenith
        </a>
    </div>
</div>
@endsection