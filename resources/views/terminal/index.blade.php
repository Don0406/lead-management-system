@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8" data-aos="fade-up">
    
    {{-- Header --}}
    <div class="flex justify-between items-end border-b border-slate-100 pb-8">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-2">Secure Correspondence</span>
            <h2 class="brand-font text-4xl text-[#5A4651] italic">Mail Terminal<span class="text-[#AEA181]">.</span></h2>
        </div>
        <div class="text-right">
            <span class="px-4 py-2 bg-[#FDFCFB] border border-slate-100 text-[8px] font-black uppercase tracking-widest text-slate-400">
                Liaison: {{ $chatPartner->name ?? 'System' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-0 bg-white border border-slate-100 shadow-sm min-h-[700px]">
        
        {{-- Sidebar 1: Contact List --}}
        @unless(auth()->user()->role === 'client')
        <div class="lg:col-span-1 border-r border-slate-50 flex flex-col bg-[#FDFCFB]">
            <div class="p-6 border-b border-slate-100 bg-white">
                <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-[#5A4651]">Active Registry</h4>
            </div>
            <div class="flex-1 overflow-y-auto">
                @forelse($contacts as $contact)
                    <a href="{{ route('terminal.index', ['client_id' => $contact->id]) }}" 
                       class="block p-5 border-b border-slate-50 transition-all hover:bg-white {{ ($chatPartner && $chatPartner->id == $contact->id) ? 'bg-white border-r-4 border-r-[#AEA181]' : '' }}">
                        <p class="text-[11px] font-bold text-[#5A4651] mb-1">{{ $contact->name }}</p>
                        <p class="text-[9px] text-slate-400 italic truncate">{{ $contact->email }}</p>
                    </a>
                @empty
                    <p class="p-6 text-[10px] italic text-slate-300">No active contacts.</p>
                @endforelse
            </div>
        </div>
        @endunless

        {{-- Sidebar 2: Intelligence Context --}}
        <div class="lg:col-span-1 border-r border-slate-50 p-8 bg-[#FDFCFB]">
            <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-[#5A4651] mb-6">Lead Intelligence</h4>
            
            @php
                $leadContext = $chatPartner ? \App\Models\Lead::where('email', $chatPartner->email)->first() : null;
            @endphp

            <div class="space-y-4">
                @if($leadContext)
                    <div class="p-4 border border-[#AEA181]/20 bg-white space-y-3">
                        <div>
                            <p class="text-[8px] uppercase text-slate-400 font-black mb-1">Contract Value</p>
                            <p class="text-xs font-bold text-[#5A4651]">${{ number_format($leadContext->value ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] uppercase text-slate-400 font-black mb-1">Origin Source</p>
                            <p class="text-[10px] font-bold text-[#AEA181] uppercase tracking-tighter">{{ $leadContext->source ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] uppercase text-slate-400 font-black mb-1">Registry Status</p>
                            <p class="text-[9px] font-black text-[#5A4651] uppercase">{{ $leadContext->status }}</p>
                        </div>
                    </div>
                @endif

                <div class="p-4 bg-[#5A4651]/5 border border-[#5A4651]/10">
                    <p class="text-[10px] text-slate-500 leading-relaxed italic">
                        Communications are encrypted and logged for project accuracy.
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Chat Interface --}}
        <div class="{{ (auth()->user()->role === 'client') ? 'lg:col-span-4' : 'lg:col-span-3' }} flex flex-col h-[700px]">
            
            {{-- Message History --}}
            <div class="flex-1 p-8 overflow-y-auto space-y-6 bg-white" id="chat-container">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] {{ $msg->sender_id == auth()->id() ? 'bg-[#5A4651] text-white' : 'bg-[#FDFCFB] border border-slate-100 text-[#5A4651]' }} p-6 shadow-sm relative">
                            {{-- Changed $msg->body to $msg->message to match DB --}}
                            <p class="text-xs italic leading-relaxed">{{ $msg->message }}</p>
                            <span class="text-[7px] uppercase tracking-[0.2em] mt-3 block opacity-40 font-black">
                                {{ $msg->created_at->format('M d, H:i') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-terminal text-slate-100 text-4xl mb-4"></i>
                            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-300 italic">No previous correspondence found.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Input Bar --}}
            <div class="p-6 border-t border-slate-50 bg-[#FDFCFB]">
                @if($chatPartner)
                <form action="{{ route('terminal.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $chatPartner->id }}">
                    
                    {{-- Changed name="body" to name="message" to match DB --}}
                    <input type="text" name="message" required autocomplete="off"
                        class="flex-1 bg-white border border-slate-100 text-sm italic p-4 focus:border-[#AEA181] outline-none transition-all shadow-inner" 
                        placeholder="Establish message protocol...">
                        
                    <button type="submit" class="px-10 bg-[#5A384B] text-white text-[9px] font-black uppercase tracking-[0.3em] hover:bg-[#5A4651] transition-all shadow-md">
                        Dispatch
                    </button>
                </form>
                @else
                <div class="text-center py-4">
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest italic font-bold">Select a liaison to begin transmission</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('chat-container');
        if(container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
@endsection