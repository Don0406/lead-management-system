@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-10" data-aos="fade-up">
    
    {{-- NAVIGATION HUB --}}
    <div class="flex flex-wrap gap-8 mb-12 border-b border-slate-100 pb-2">
        <a href="{{ route('dashboard') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-[#5A4651] transition-all pb-6">
            Project Overview
        </a>
        <a href="{{ route('orders.index') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-[#5A4651] border-b-2 border-[#AEA181] pb-6">
            My Orders
        </a>
        <a href="{{ route('terminal.index') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-[#5A4651] transition-all pb-6">
            Mail Terminal
        </a>
    </div>

    {{-- HEADER --}}
    <div class="text-center space-y-4 mb-12">
        <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block">Acquisition Ledger</span>
        <h2 class="brand-font text-5xl text-[#5A4651] italic">Order History<span class="text-[#AEA181]">.</span></h2>
    </div>

    {{-- ORDERS TABLE --}}
    <div class="bg-white border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-50">
                    <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Reference</th>
                    <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Asset / Service</th>
                    <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                    <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Investment</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($orders as $order)
                    <tr class="hover:bg-[#FDFCFB] transition-colors">
                        <td class="p-8 text-xs font-mono text-slate-400">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-8">
                            <p class="text-sm font-bold text-[#5A4651] uppercase tracking-widest">{{ $order->product->name ?? 'Custom Service' }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 italic">{{ $order->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="p-8">
                            <span class="px-3 py-1 text-[8px] font-black uppercase tracking-widest 
                                {{ $order->status == 'completed' ? 'bg-green-50 text-green-600' : 'bg-[#AEA181]/10 text-[#AEA181]' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-8 text-right text-sm font-light text-[#5A4651]">
                            ${{ number_format($order->total, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-20 text-center">
                            <p class="text-[10px] uppercase tracking-[0.3em] text-slate-300">No active acquisitions found in your ledger.</p>
                            <a href="{{ route('dashboard') }}" class="mt-6 inline-block text-[9px] font-black uppercase text-[#AEA181] border-b border-[#AEA181] pb-1">
                                Return to Project Overview
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection