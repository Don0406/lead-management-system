@extends('layouts.app')

@section('content')
<div class="space-y-10" data-aos="fade-up">
    
    {{-- 1. TOP NAVIGATION & BREADCRUMB --}}
    <div class="flex justify-between items-center border-b border-slate-100 pb-6">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-2">Lead Intelligence</span>
            <h2 class="brand-font text-4xl text-[#5A4651] italic leading-tight">
                {{ $lead->first_name }} {{ $lead->last_name }}<span class="text-[#AEA181]">.</span>
            </h2>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('leads.index') }}" class="px-6 py-3 border border-slate-100 text-[8px] font-black uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">
                ← Back to Ledger
            </a>
            <a href="{{ route('leads.edit', $lead) }}" class="px-6 py-3 bg-[#5A4651] text-white text-[8px] font-black uppercase tracking-widest hover:bg-[#3d3037] transition-all">
                Edit Principal
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- 2. PRIMARY DOSSIER (Left Column) --}}
        <div class="lg:col-span-2 space-y-10">
            <div class="bg-white border border-slate-100 p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#AEA181]"></div>
                
                <div class="flex items-center gap-8 mb-12">
                    <div class="w-20 h-20 bg-[#5A4651] text-white flex items-center justify-center text-2xl font-black italic">
                        {{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[#AEA181] mb-1">Current Classification</p>
                        <span class="px-4 py-1 border border-[#AEA181]/30 text-[#AEA181] text-[10px] font-black uppercase tracking-widest bg-[#FDFCFB]">
                            {{ str_replace('_', ' ', $lead->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block mb-2">Corporate Identity</label>
                        <p class="text-sm italic text-[#5A4651] font-bold">{{ $lead->company ?? 'Private Principal' }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">{{ $lead->title ?? 'Executive' }}</p>
                    </div>
                    <div>
                        <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block mb-2">Secure Contact</label>
                        <p class="text-sm italic text-[#5A4651] font-bold">{{ $lead->email }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 tracking-widest">{{ $lead->phone ?? 'NO PHONE LOGGED' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[8px] uppercase font-black text-[#AEA181] tracking-[0.2em] block mb-2">Primary Intelligence / Inquiry Notes</label>
                        <div class="bg-[#FDFCFB] border border-slate-50 p-6 text-sm italic text-[#5A4651] leading-relaxed whitespace-pre-line">
                            {{ $lead->notes ?? 'No primary intelligence recorded.' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. ACTIVITY TIMELINE --}}
            <div class="space-y-6">
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#5A4651]">Activity Protocol</h4>
                <div class="bg-white border border-slate-100 p-8 space-y-8 relative">
                    <div class="absolute left-[41px] top-10 bottom-10 w-[1px] bg-slate-100"></div>

                    {{-- This section handles the related notes IF you set up Option B later --}}
                    @if(isset($lead->leadNotes) && count($lead->leadNotes) > 0)
                        @foreach($lead->leadNotes as $note)
                        <div class="flex items-start gap-6 relative z-10">
                            <div class="w-2 h-2 rounded-full bg-slate-200 mt-2"></div>
                            <div>
                                <p class="text-[11px] text-[#5A4651] italic leading-relaxed">"{{ $note->content }}"</p>
                                <p class="text-[8px] text-slate-400 uppercase mt-1 tracking-tighter">
                                    Logged by {{ $note->user->name ?? 'System' }} • {{ $note->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if($lead->contacted_at)
                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-2 h-2 rounded-full bg-[#AEA181] mt-2"></div>
                        <div>
                            <p class="text-[11px] font-bold text-[#5A4651]">First Contact Established</p>
                            <p class="text-[9px] text-slate-400 uppercase tracking-tighter">{{ $lead->contacted_at->format('M d, Y @ H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-2 h-2 rounded-full bg-green-400 mt-2 shadow-[0_0_10px_rgba(74,222,128,0.5)]"></div>
                        <div>
                            <p class="text-[11px] font-bold text-[#5A4651]">Lead Initialized</p>
                            <p class="text-[9px] text-slate-400 uppercase tracking-tighter">
                                {{ $lead->created_at->format('M d, Y @ H:i') }} 
                                @if($lead->creator) by {{ $lead->creator->name }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. SIDEBAR ACTIONS (Right Column) --}}
        <div class="space-y-8">
            
            {{-- Terminal Shortcut --}}
            <div class="bg-[#5A4651] p-10 text-white relative overflow-hidden">
                <div class="absolute top-[-20px] right-[-20px] opacity-5 text-6xl italic brand-font">MT</div>
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[#AEA181] mb-6">Client Correspondence</p>
                <h4 class="text-xl italic brand-font mb-6 leading-tight">Engage Secure Messaging Terminal</h4>
                
               <a href="{{ route('terminal.index', ['client_id' => $lead->created_by]) }}" class="block w-full py-4 bg-[#AEA181] text-white text-[9px] font-black uppercase tracking-[0.3em] text-center hover:bg-white hover:text-[#5A4651] transition-all">
                    Open Secure Terminal
                </a>
            </div>

            {{-- Workflow Actions --}}
            <div class="bg-white border border-slate-100 p-8">
                <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-[#5A4651] mb-6">Execution Actions</h4>
                <div class="space-y-3">
                    @if($lead->status == 'new')
                        <form action="{{ route('leads.markContacted', $lead) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 border border-[#AEA181]/30 text-[#AEA181] text-[8px] font-black uppercase tracking-widest hover:bg-[#AEA181] hover:text-white transition-all">
                                <i class="fas fa-phone me-2"></i> Mark Contacted
                            </button>
                        </form>
                    @endif

                    <a href="mailto:{{ $lead->email }}" class="block w-full py-3 border border-slate-100 text-slate-400 text-[8px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all">
                        <i class="fas fa-envelope me-2"></i> External Email
                    </a>

                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Terminate this lead record permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 text-red-200 text-[8px] font-black uppercase tracking-widest hover:text-red-600 transition-all mt-4">
                            <i class="fas fa-trash me-2"></i> Terminate Record
                        </button>
                    </form>
                </div>
            </div>

            {{-- Assignment Info --}}
            <div class="bg-[#FDFCFB] border border-slate-100 p-8">
                <p class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-300 mb-4">Assigned Specialist</p>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-[#5A4651]">
                        {{ substr($lead->assignedUser->name ?? 'U', 0, 1) }}
                    </div>
                    <p class="text-[11px] font-bold text-[#5A4651] italic">{{ $lead->assignedUser->name ?? 'Unassigned' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection