@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-20" data-aos="fade-up">
    <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-4">Governance Protocol</span>
    <h1 class="brand-font text-6xl text-[#5A4651] italic mb-12">{{ $title }}<span class="text-[#AEA181]">.</span></h1>
    
    <div class="prose prose-slate max-w-none">
        <div class="bg-white border border-slate-100 p-12 shadow-sm leading-relaxed text-slate-600 space-y-8">
            {!! $content !!}
        </div>
    </div>

    <div class="mt-12 flex justify-between items-center border-t border-slate-100 pt-8">
        <p class="text-[9px] uppercase tracking-widest text-slate-400 font-bold">Last Revised: {{ now()->format('M d, Y') }}</p>
        <a href="{{ route('dashboard') }}" class="text-[9px] font-black uppercase tracking-widest text-[#AEA181] hover:text-[#5A384B]">Return to Command →</a>
    </div>
</div>
@endsection
