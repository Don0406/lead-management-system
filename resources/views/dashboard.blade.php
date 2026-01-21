@extends('layouts.app')

@section('content')
<div class="space-y-12" data-aos="fade-up">

    {{-- 1. ALERTS --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-100 p-4 text-green-700 text-[10px] uppercase tracking-[0.2em] font-black mb-6 flex items-center justify-between">
            <span><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-900">&times;</button>
        </div>
    @endif

    {{-- 2. HEADER & WELCOME --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-6">
        <div class="flex items-center gap-6">
            <div class="relative hidden lg:block">
                <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=5A4651&color=fff' }}" 
                     class="w-20 h-20 rounded-full object-cover border-2 border-[#AEA181] p-1">
                <span class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-2">Systems Command</span>
                <h2 class="brand-font text-5xl text-[#5A4651] italic leading-tight">
                    @if(auth()->user()->role === 'admin') Admin Control
                    @elseif(auth()->user()->role === 'sales_manager') Sales Oversight
                    @else Representative Desk @endif<span class="text-[#AEA181]">.</span>
                </h2>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 mt-2 font-bold">
                    Identity: {{ auth()->user()->name }} // Role: {{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}
                </p>
            </div>
        </div>
    </div>

    {{-- 3. ROLE-BASED STATS GRID --}}
    {{-- Notice: $stats is now filtered by the Controller logic we updated earlier --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $statConfig = [
                ['label' => (auth()->user()->role === 'sales_rep' ? 'My Leads' : 'Total Pipeline'), 'val' => $stats['total'] ?? 0, 'color' => '#5A4651'],
                ['label' => 'New Inquiries', 'val' => $stats['new'] ?? 0, 'color' => '#AEA181'],
                ['label' => 'Total Contacted', 'val' => $stats['contacted'] ?? 0, 'color' => '#5A384B'],
                ['label' => 'Qualified Assets', 'val' => $stats['qualified'] ?? 0, 'color' => '#8C7E6C'],
            ];
        @endphp
        @foreach($statConfig as $s)
        <div class="bg-white p-8 border border-slate-100 group hover:border-[#AEA181] transition-all duration-500">
            <p class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2">{{ $s['label'] }}</p>
            <h3 class="text-4xl font-light tracking-tighter" style="color: {{ $s['color'] }}">{{ $s['val'] }}</h3>
        </div>
        @endforeach
    </div>

    {{-- 4. TEAM GOVERNANCE (Admin: User Management | Manager: Assignment Tracking) --}}
    @if(auth()->user()->role === 'admin')
        <div class="pt-8 border-t border-slate-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#5A4651]">System Personnel</h4>
                    <p class="text-[9px] text-slate-400 italic">Global staff authorization and role configuration.</p>
                </div>
                <button onclick="document.getElementById('provisionModal').classList.remove('hidden')" 
                    class="px-6 py-3 border border-[#AEA181]/30 text-[8px] font-black uppercase tracking-widest text-[#AEA181] hover:bg-[#AEA181] hover:text-white transition-all">
                    + Provision Staff Account
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(\App\Models\User::whereIn('role', ['admin', 'sales_manager', 'sales_rep'])->get() as $member)
                <div class="bg-white p-6 border border-slate-100 flex items-center justify-between group hover:border-[#AEA181]/50 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-50">
                            <img src="{{ $member->avatar ? asset('storage/'.$member->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=FDFCFB&color=AEA181' }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-[#5A4651]">{{ $member->name }}</p>
                            <p class="text-[8px] uppercase tracking-widest text-slate-400">{{ str_replace('_', ' ', $member->role) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openEditModal('{{ $member->id }}', '{{ $member->name }}', '{{ $member->email }}', '{{ $member->role }}')" class="p-2 text-slate-300 hover:text-[#AEA181] transition-colors">
                            <i class="fas fa-pen-nib text-[10px]"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

   {{-- 5. RECENT LEADS TABLE (Restored Fields) --}}
<div class="bg-white border border-slate-100 overflow-hidden">
    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
        <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#5A4651]">Lead Registry Activity</h4>
        <a href="{{ route('leads.index') }}" class="text-[9px] font-black uppercase tracking-widest text-[#AEA181] hover:text-[#5A384B]">Full Ledger →</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#FDFCFB]">
                    <th class="px-8 py-4 text-[9px] uppercase tracking-widest text-slate-400 font-black border-b border-slate-50">Lead Principal</th>
                    <th class="px-8 py-4 text-[9px] uppercase tracking-widest text-slate-400 font-black border-b border-slate-50">Source</th>
                    <th class="px-8 py-4 text-[9px] uppercase tracking-widest text-slate-400 font-black border-b border-slate-50">Value</th>
                    <th class="px-8 py-4 text-[9px] uppercase tracking-widest text-slate-400 font-black border-b border-slate-50">Status</th>
                    <th class="px-8 py-4 text-[9px] uppercase tracking-widest text-slate-400 font-black border-b border-slate-50 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($recentLeads as $lead)
                <tr class="group hover:bg-[#FDFCFB]/50 transition-colors">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-[#5A4651] text-white flex items-center justify-center text-[10px] font-black">
                                {{ substr($lead->first_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#5A4651] mb-0.5">{{ $lead->full_name }}</p>
                                <p class="text-[9px] text-slate-400 italic">{{ $lead->company ?? 'Independent' }}</p>
                            </div>
                        </div>
                    </td>
                    {{-- RESTORED: Source --}}
                    <td class="px-8 py-5">
                        <span class="text-[10px] text-slate-500 font-medium uppercase tracking-tighter">
                            {{ $lead->source ?? 'Direct' }}
                        </span>
                    </td>
                    {{-- RESTORED: Value --}}
                    <td class="px-8 py-5">
                        <span class="text-[11px] font-bold text-[#5A4651]">
                            ${{ number_format($lead->value ?? 0, 2) }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-[8px] font-black uppercase tracking-widest px-3 py-1 border border-[#AEA181]/20 text-[#AEA181] bg-[#AEA181]/5">
                            {{ $lead->status }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-3 items-center">
                            {{-- NEW: Terminal Message Link --}}
                            @php
                                // Find if this lead has a registered User account to chat with
                                $clientUser = \App\Models\User::where('email', $lead->email)->first();
                            @endphp
                            
                            @if($clientUser)
                                <a href="{{ route('terminal.index', ['client_id' => $clientUser->id]) }}" 
                                   class="text-[#AEA181] hover:text-[#5A384B] transition-colors" title="Open Terminal">
                                    <i class="fas fa-terminal text-xs"></i>
                                </a>
                            @endif

                            <a href="{{ route('leads.show', $lead) }}" class="text-slate-300 hover:text-[#5A384B] transition-colors"><i class="fas fa-eye text-xs"></i></a>
                            <a href="{{ route('leads.edit', $lead) }}" class="text-slate-300 hover:text-[#AEA181] transition-colors"><i class="fas fa-edit text-xs"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Empty state remains same --}}
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

{{-- MODALS UNCHANGED FROM YOUR PREVIOUS CODE --}}
{{-- ... Provision Modal & Edit Staff Modal ... --}}

@endsection