@extends('layouts.app')

@section('content')
<div class="space-y-6" data-aos="fade-up">

    {{-- 1. HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-[#5A4651] p-6 shadow-2xl">
        <div class="flex items-center gap-4">
            <div class="h-10 w-1 bg-[#AEA181]"></div>
            <div>
                <h2 class="text-white brand-font text-2xl italic">Registry Archive<span class="text-[#AEA181]">.</span></h2>
                <p class="text-[8px] uppercase tracking-[0.3em] text-slate-300">
                    {{ auth()->user()->role == 'sales_rep' ? 'Your Assigned Records' : 'Global Intelligence Ledger' }}: {{ $leads->total() }}
                </p>
            </div>
        </div>
        
        <div class="mt-4 md:mt-0 flex flex-grow max-w-2xl mx-8">
            <form action="{{ route('leads.index') }}" method="GET" class="w-full flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="SEARCH BY NAME, EMAIL, OR COMPANY..." 
                    class="w-full bg-[#FDFCFB]/10 border border-white/10 px-4 py-2 text-[10px] text-white placeholder-slate-400 focus:outline-none focus:border-[#AEA181] transition-all">
                <button class="bg-[#AEA181] px-6 py-2 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white hover:text-[#5A4651] transition-all">
                    Query
                </button>
            </form>
        </div>
    </div>

    {{-- 2. DATA GRID --}}
    <div class="bg-white border border-slate-200 shadow-sm">
        {{-- FILTER TABS --}}
        <div class="flex border-b border-slate-100 bg-slate-50/50 overflow-x-auto">
            @foreach(['all' => 'Full Registry', 'new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified'] as $key => $label)
                <a href="{{ route('leads.index', ['status' => $key == 'all' ? '' : $key]) }}" 
                   class="px-6 py-3 text-[9px] font-black uppercase tracking-widest border-r border-slate-100 hover:bg-white transition-all whitespace-nowrap {{ (request('status') == $key || (request('status') == '' && $key == 'all')) ? 'bg-white text-[#5A4651] border-b-2 border-b-[#AEA181]' : 'text-slate-400' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white">
                        <th class="px-6 py-4 text-[8px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">ID-REF</th>
                        <th class="px-6 py-4 text-[8px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Principal Identity</th>
                        <th class="px-6 py-4 text-[8px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Corp/Entity</th>
                        <th class="px-6 py-4 text-[8px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Protocol Status</th>
                        <th class="px-6 py-4 text-[8px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Assignment</th>
                        <th class="px-6 py-4 text-[8px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-3 text-[10px] font-mono text-slate-300">#{{ str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-3">
                            <p class="text-[11px] font-bold text-[#5A4651]">{{ $lead->first_name }} {{ $lead->last_name }}</p>
                            <p class="text-[9px] text-slate-400 italic lowercase">{{ $lead->email }}</p>
                        </td>
                        <td class="px-6 py-3 text-[10px] text-slate-600 font-medium uppercase tracking-tight">
                            {{ $lead->company ?? '—' }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 border {{ $lead->status == 'qualified' ? 'border-green-200 text-green-600 bg-green-50' : 'border-slate-200 text-slate-500' }}">
                                {{ $lead->status }}
                            </span>
                        </td>
                        
                        {{-- ROLE BASED ASSIGNMENT COLUMN --}}
                        <td class="px-6 py-3">
                            @if(auth()->user()->role === 'sales_manager' || auth()->user()->isAdmin())
                                <select onchange="reassignLead({{ $lead->id }}, this.value)" class="text-[9px] bg-slate-50 border-slate-200 px-2 py-1 uppercase font-bold tracking-tighter focus:ring-1 focus:ring-[#AEA181] outline-none">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $staff)
                                        <option value="{{ $staff->id }}" {{ $lead->assigned_to == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-[#AEA181]"></div>
                                    <span class="text-[9px] text-slate-500 uppercase tracking-tighter">{{ $lead->assignedUser->name ?? 'Unassigned' }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-3 text-right">
                            <div class="flex justify-end gap-3 items-center">
                                {{-- REP ONLY: MARK CONTACTED --}}
                                @if(auth()->user()->role === 'sales_rep' && $lead->status === 'new')
                                    <form action="{{ route('leads.markContacted', $lead) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-[8px] font-black text-[#AEA181] border border-[#AEA181] px-2 py-1 hover:bg-[#AEA181] hover:text-white transition-all uppercase">
                                            Log Contact
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('leads.show', $lead) }}" class="text-slate-300 hover:text-[#5A4651]"><i class="fas fa-eye text-[10px]"></i></a>
                                
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Confirm Deletion?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-200 hover:text-red-400"><i class="fas fa-trash text-[10px]"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center text-[10px] uppercase tracking-widest text-slate-400 italic">No records found in this sector</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-slate-50 p-4 border-t border-slate-100 flex justify-between items-center">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Authorized Access Only</p>
            <div class="custom-pagination">
                {{ $leads->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

{{-- 3. INTERACTIVE LOGIC SCRIPT --}}
<script>
    function reassignLead(leadId, userId) {
        fetch(`/leads/${leadId}/assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ assigned_to: userId })
        })
        .then(response => response.json())
        .then(data => {
            // Optional: Show a small toast notification
            console.log('Protocol Updated:', data.message);
        })
        .catch(error => alert('Security Error: Could not reassign record.'));
    }
</script>

<style>
    .custom-pagination nav svg { height: 14px; width: 14px; display: inline; }
    .custom-pagination nav div:first-child { display: none; }
    .custom-pagination span, .custom-pagination a { 
        font-size: 10px !important; 
        font-weight: 900 !important; 
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 4px 8px !important;
    }
</style>
@endsection