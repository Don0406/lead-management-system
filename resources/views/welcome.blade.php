@extends('layouts.app')

@section('title', 'LeadBridge | Professional Lead Management Architecture')

@section('content')
<div class="font-sans antialiased selection:bg-[#AEA181] selection:text-white">

    {{-- 1. MAJESTIC HERO SECTION --}}
    <section class="relative py-32 md:py-44 overflow-hidden bg-[#FDFCFB]">
        {{-- Subtle Background Elements --}}
        <div class="absolute inset-0 pattern-bg opacity-40"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-[#5A384B] opacity-[0.03] blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[-5%] w-[500px] h-[500px] bg-[#37462B] opacity-[0.05] blur-[100px]"></div>
        </div>
        
        <div class="relative z-10 text-center space-y-12 px-6 max-w-5xl mx-auto" data-aos="fade-up">
            <div class="space-y-6">
                <span class="text-[10px] font-black uppercase tracking-[0.9em] text-[#37462B] block mb-4">Lead Management System 1.0</span>
                
                <h1 class="text-5xl md:text-8xl font-extralight text-[#5A4651] leading-[1.1] tracking-tighter">
                    Crafting <span class="brand-font italic text-[#AEA181] drop-shadow-sm">Connections.</span>
                </h1>
                
                <p class="font-normal uppercase text-[11px] tracking-[0.7em] text-[#5A384B] opacity-70 mt-8 max-w-2xl mx-auto leading-relaxed">
                    Converting complex possibilities into <span class="text-[#37462B] font-bold">architectural growth.</span>
                </p>
            </div>

            {{-- STAR RATINGS --}}
            <div class="flex flex-col items-center gap-3 pt-4" data-aos="fade-up" data-aos-delay="200">
                <div class="flex gap-1 text-[#AEA181]">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-400">Industry-Leading Precision</span>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-8 justify-center items-center pt-8">
                <a href="#book-demo" class="group relative px-14 py-6 bg-[#5A4651] text-white text-[11px] font-black uppercase tracking-[0.4em] overflow-hidden transition-all hover:scale-105 shadow-2xl no-underline">
                    <span class="relative z-10">Request a Demo</span>
                    <div class="absolute inset-0 bg-[#AEA181] translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                </a>
                
                @auth
                    <a href="{{ route('leads.index') }}" class="group text-[#5A4651] text-[11px] font-black uppercase tracking-[0.4em] flex items-center gap-4 hover:opacity-70 transition-opacity no-underline">
                        Enter The Ledger
                        <span class="w-8 h-[1px] bg-[#AEA181] group-hover:w-12 transition-all"></span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group text-[#5A4651] text-[11px] font-black uppercase tracking-[0.4em] flex items-center gap-4 hover:opacity-70 transition-opacity no-underline">
                        Access Portal
                        <span class="w-8 h-[1px] bg-[#AEA181] group-hover:w-12 transition-all"></span>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- 2. GENESIS & VISION SECTION --}}
    <section id="vision" class="py-32 bg-white border-y border-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 md:grid-cols-2 gap-24 items-center">
            <div data-aos="fade-right">
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-6">The Genesis</span>
                <h2 class="brand-font text-5xl text-[#5A4651] italic mb-8">Why LeadBridge?</h2>
                <div class="space-y-6 text-slate-500 font-light italic text-sm leading-relaxed">
                    <p>LeadBridge was conceived in the space between raw data and human intuition. We recognized that most Lead Management Systems were built for storage, not for <strong>momentum.</strong></p>
                    <p>By distilling noise into actionable intelligence, we ensure that every professional connection has the foundation it needs to thrive.</p>
                </div>
            </div>
            <div class="relative p-12 bg-[#FDFCFB] border border-slate-100" data-aos="fade-left">
                <div class="absolute -top-4 -left-4 w-12 h-12 border-t-2 border-l-2 border-[#AEA181]"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#5A384B] block mb-4">Our Vision</span>
                <p class="text-2xl font-extralight text-[#5A4651] tracking-tight leading-relaxed">
                    To redefine the <span class="text-[#AEA181]">gold standard</span> for lead management, transforming organizational potential into a legacy of growth.
                </p>
            </div>
        </div>
    </section>

    {{-- 3. CAPABILITIES SECTION --}}
    <section class="max-w-7xl mx-auto py-40 px-8">
        <div class="flex items-center gap-12 mb-32" data-aos="fade-up">
            <div class="text-left">
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-2">LMS Portfolio</span>
                <h2 class="brand-font text-5xl text-[#5A4651] italic">The Architecture of Momentum</h2>
            </div>
            <div class="flex-1 h-[1px] bg-gradient-to-r from-[#AEA181]/40 to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @php
                $services = [
                    ['n'=>'01', 't'=>'Lead Tracking','d'=>'Detailed monitoring of prospect engagement from initial touchpoint to final conversion.'],
                    ['n'=>'02', 't'=>'Smart Assignment','d'=>'Automated distribution logic to match prospects with representatives.'],
                    ['n'=>'03', 't'=>'Sales Pipeline','d'=>'A high-fidelity visualization of your revenue architecture and funnel velocity.'],
                    ['n'=>'04', 't'=>'Follow-Up Protocol','d'=>'Precision scheduling and interaction logging to maintain consistent client momentum.'],
                    ['n'=>'05', 't'=>'Secure Governance','d'=>'Enterprise-grade role-based access controls protecting sensitive data.'],
                    ['n'=>'06', 't'=>'Performance Intelligence','d'=>'Advanced analytical insights into conversion rates and sales productivity.']
                ];
            @endphp

            @foreach($services as $s)
            <a href="{{ route('services.show', Str::slug($s['t'])) }}" 
               class="group relative bg-white p-12 border border-slate-100 transition-all duration-700 hover:shadow-[0_30px_60px_-15px_rgba(90,56,75,0.1)] block no-underline"
               data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                
                <div class="absolute top-0 left-0 w-[2px] h-0 bg-[#AEA181] group-hover:h-full transition-all duration-1000"></div>
                
                <div class="space-y-10">
                    <div class="text-[10px] font-black text-[#AEA181] tracking-[0.5em] opacity-40 group-hover:opacity-100 transition-opacity">
                        // {{ $s['n'] }}
                    </div>
                    <div>
                        <h3 class="font-bold text-xs uppercase tracking-[0.3em] text-[#5A4651] mb-5 group-hover:text-[#AEA181] transition-colors">
                            {{ $s['t'] }}
                        </h3>
                        <p class="text-[12px] leading-relaxed text-slate-500 font-light italic mb-6">
                            {{ $s['d'] }}
                        </p>
                        <span class="text-[9px] font-black uppercase tracking-widest text-[#AEA181] opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0 inline-block">
                            View Blueprint →
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- 4. INQUIRY FORM SECTION --}}
    <section id="book-demo" class="bg-[#5A4651] py-40 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-[#FDFCFB] to-transparent opacity-10"></div>
        
        <div class="max-w-4xl mx-auto px-8 relative z-10" data-aos="zoom-in">
            <div class="bg-white p-12 md:p-20 rounded-none shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 bg-[#AEA181] text-white px-12 py-3 text-[9px] font-black uppercase tracking-[0.5em] shadow-lg">
                    System Inquiry
                </div>

                <div class="mb-16 text-center">
                    <h2 class="brand-font text-4xl text-[#5A4651] italic mb-4">Consult our Specialists</h2>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Scale your lead management architecture</p>
                </div>

                {{-- Validation Errors Display --}}
                @if ($errors->any())
                    <div class="mb-10 p-6 bg-red-50 border-l-2 border-red-500">
                        <ul class="list-none space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-[11px] text-red-600 italic font-medium uppercase tracking-wider">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('landing.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                    @csrf
                    @foreach([
                        ['label' => 'Principal Name', 'name' => 'name', 'type' => 'text', 'placeholder' => 'Full Name'],
                        ['label' => 'Business Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'email@company.com'],
                        ['label' => 'Contact Number', 'name' => 'phone', 'type' => 'text', 'placeholder' => '+1 (555) 000-0000'],
                        ['label' => 'Organization', 'name' => 'company', 'type' => 'text', 'placeholder' => 'Business Name'],
                        ['label' => 'Establish Password', 'name' => 'password', 'type' => 'password', 'placeholder' => 'Min. 8 Characters'],
                        ['label' => 'Confirm Password', 'name' => 'password_confirmation', 'type' => 'password', 'placeholder' => 'Repeat Password']
                    ] as $field)
                    <div class="space-y-3">
                        <label class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.2em] opacity-60">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" required 
                               class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-3 text-[13px] italic placeholder-slate-200 bg-transparent transition-colors shadow-none" 
                               placeholder="{{ $field['placeholder'] }}">
                    </div>
                    @endforeach

                    <div class="md:col-span-2 space-y-3">
                        <label class="text-[9px] uppercase font-black text-[#5A4651] tracking-[0.2em] opacity-60">Primary Interest</label>
                        <select name="interest" required 
                                 class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] py-4 text-[13px] italic bg-transparent text-slate-500 shadow-none">
                            <option value="">Select a Solution</option>
                            @foreach($services as $s)
                                <option value="{{ $s['t'] }}">{{ $s['t'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 pt-12">
                        <button type="submit" class="w-full py-6 bg-[#5A384B] text-white text-[11px] font-black uppercase tracking-[0.6em] hover:bg-[#5A4651] transition-all shadow-xl group flex items-center justify-center gap-4 border-0">
                            Initialize Account & Register
                            <span class="w-10 h-[1px] bg-[#AEA181] group-hover:w-16 transition-all"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="py-20 text-center bg-[#FDFCFB] pattern-bg opacity-60">
        <a href="#" class="text-[9px] font-black uppercase tracking-[0.5em] text-slate-400 hover:text-[#5A384B] transition-all no-underline">
            ↑ Return to Zenith
        </a>
    </div>
</div>
@endsection