@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-10" data-aos="fade-up">
    
    {{-- 1. HEADER --}}
    <div class="flex justify-between items-center border-b border-slate-100 pb-6">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-2">Modification Protocol</span>
            <h2 class="brand-font text-4xl text-[#5A4651] italic leading-tight">
                Edit Principal<span class="text-[#AEA181]">.</span>
            </h2>
        </div>
        <a href="{{ route('leads.show', $lead) }}" class="px-6 py-3 border border-slate-100 text-[8px] font-black uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">
            ← Cancel & Exit
        </a>
    </div>

    {{-- 2. FORM CONTAINER --}}
    <div class="bg-white border border-slate-100 relative overflow-hidden shadow-sm">
        <div class="absolute top-0 left-0 w-1 h-full bg-[#5A4651]"></div>
        
        <form action="{{ route('leads.update', $lead) }}" method="POST" class="p-10 space-y-8">
            @csrf
            @method('PUT')

            {{-- SECTION: IDENTITY --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $lead->first_name) }}" 
                        class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic text-[#5A4651] focus:border-[#AEA181] outline-none transition-all @error('first_name') border-red-300 @enderror" required>
                    @error('first_name') <p class="text-[10px] text-red-400 uppercase font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $lead->last_name) }}" 
                        class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic text-[#5A4651] focus:border-[#AEA181] outline-none transition-all @error('last_name') border-red-300 @enderror" required>
                    @error('last_name') <p class="text-[10px] text-red-400 uppercase font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- SECTION: CONTACT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-50 pt-8">
                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Secure Email</label>
                    <input type="email" name="email" value="{{ old('email', $lead->email) }}" 
                        class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic text-[#5A4651] focus:border-[#AEA181] outline-none transition-all" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Phone Frequency</label>
                    <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" 
                        class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic text-[#5A4651] focus:border-[#AEA181] outline-none transition-all">
                </div>
            </div>

            {{-- SECTION: CORPORATE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-50 pt-8">
                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Organization</label>
                    <input type="text" name="company" value="{{ old('company', $lead->company) }}" 
                        class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic text-[#5A4651] focus:border-[#AEA181] outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Professional Title</label>
                    <input type="text" name="title" value="{{ old('title', $lead->title) }}" 
                        class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic text-[#5A4651] focus:border-[#AEA181] outline-none transition-all">
                </div>
            </div>

            {{-- SECTION: CLASSIFICATION --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-slate-50 pt-8">
                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Source</label>
                    <select name="source" class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-[11px] font-bold uppercase tracking-widest text-[#5A4651] focus:border-[#AEA181] outline-none transition-all">
                        <option value="Landing Page" {{ old('source', $lead->source) == 'Landing Page' ? 'selected' : '' }}>Landing Page</option>
                        <option value="Website" {{ old('source', $lead->source) == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Referral" {{ old('source', $lead->source) == 'Referral' ? 'selected' : '' }}>Referral</option>
                        <option value="Social Media" {{ old('source', $lead->source) == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                        <option value="Other" {{ old('source', $lead->source) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Current Status</label>
                    <select name="status" class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-[11px] font-bold uppercase tracking-widest text-[#AEA181] focus:border-[#AEA181] outline-none transition-all">
                        <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="proposal" {{ old('status', $lead->status) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="negotiation" {{ old('status', $lead->status) == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                        <option value="closed_won" {{ old('status', $lead->status) == 'closed_won' ? 'selected' : '' }}>Won</option>
                        <option value="closed_lost" {{ old('status', $lead->status) == 'closed_lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Valuation ($)</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $lead->value) }}" 
                        class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm font-bold text-[#AEA181] focus:border-[#AEA181] outline-none transition-all">
                </div>
            </div>

            {{-- SECTION: ASSIGNMENT --}}
            <div class="space-y-2 border-t border-slate-50 pt-8">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Assigned Intelligence Officer</label>
                <select name="assigned_to" class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-[11px] font-bold text-[#5A4651] focus:border-[#AEA181] outline-none transition-all">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} — {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- SECTION: NOTES --}}
            <div class="space-y-2 border-t border-slate-50 pt-8">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Primary Intelligence Notes</label>
                <textarea name="notes" rows="5" 
                    class="w-full bg-[#FDFCFB] border border-slate-100 p-4 text-sm italic text-[#5A4651] leading-relaxed focus:border-[#AEA181] outline-none transition-all placeholder:text-slate-200"
                    placeholder="Enter strategic background information...">{{ old('notes', $lead->notes) }}</textarea>
            </div>

            {{-- FORM FOOTER --}}
            <div class="pt-10 flex justify-end gap-4">
                <button type="submit" class="px-10 py-4 bg-[#5A4651] text-white text-[10px] font-black uppercase tracking-[0.3em] hover:bg-[#3d3037] transition-all shadow-xl shadow-slate-200">
                    Commit Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection