@extends('layouts.app')

@section('title', $page['title'] . ' | LeadBridge')

@section('content')
<section class="py-32 max-w-4xl mx-auto" data-aos="fade-up">
    <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-6">{{ $page['subtitle'] }}</span>
    <h1 class="brand-font text-6xl text-[#5A4651] italic mb-12">{{ $page['title'] }}</h1>
    
    <div class="prose prose-slate leading-relaxed text-slate-500 font-light italic text-lg">
        {!! $page['content'] !!}
    </div>

    <div class="mt-20 pt-12 border-t border-slate-100">
        <a href="{{ url('/') }}" class="text-[9px] font-black uppercase tracking-[0.4em] text-[#5A4651] hover:text-[#AEA181] transition-all no-underline">
            ← Return to Hub
        </a>
    </div>
</section>
@endsection
