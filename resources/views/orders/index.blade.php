@extends('layouts.app')

@section('content')
<div class="space-y-8" data-aos="fade-up">
    
    {{-- Header --}}
    <div class="flex justify-between items-end border-b border-slate-100 pb-8">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-2">Transaction Logs</span>
            <h2 class="brand-font text-5xl text-[#5A4651] italic leading-tight">Order <span class="text-[#AEA181]">Ledger.</span></h2>
            <p class="text-[10px] uppercase tracking-widest text-slate-400 mt-2 font-bold italic underline decoration-[#AEA181]/30">
                Comprehensive history of all digital acquisitions and assets.
            </p>
        </div>
        
        <div class="hidden md:block text-right">
            <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">System Time</p>
            <p class="text-xs font-mono text-[#5A4651]">{{ now()->format('Y.m.d H:i:s') }}</p>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="bg-white border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FDFCFB]">
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.3em] text-slate-400 font-black border-b border-slate-50">Reference</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.3em] text-slate-400 font-black border-b border-slate-50">Principal</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.3em] text-slate-400 font-black border-b border-slate-50">Asset Description</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.3em] text-slate-400 font-black border-b border-slate-50">Valuation</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.3em] text-slate-400 font-black border-b border-slate-50">Status</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.3em] text-slate-400 font-black border-b border-slate-50 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($orders as $order)
                    <tr class="group hover:bg-[#FDFCFB]/80 transition-all duration-300">
                        {{-- Reference ID --}}
                        <td class="px-8 py-6">
                            <span class="font-mono text-[11px] text-[#AEA181] font-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>

                        {{-- Client/User --}}
                        <td class="px-8 py-6">
                            <p class="text-xs font-bold text-[#5A4651]">{{ $order->user->name ?? 'System Guest' }}</p>
                            <p class="text-[9px] text-slate-400 tracking-wider uppercase italic">{{ $order->user->role ?? 'Standard' }}</p>
                        </td>

                        {{-- Product --}}
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-[#5A4651] uppercase tracking-tighter">{{ $order->product->name ?? 'Custom Service' }}</p>
                            <p class="text-[9px] text-slate-400 italic">Verified Asset</p>
                        </td>

                        {{-- Total --}}
                        <td class="px-8 py-6">
                            <span class="text-xs font-bold text-[#5A4651] tabular-nums">${{ number_format($order->total, 2) }}</span>
                        </td>

                        {{-- Status Management --}}
                        <td class="px-8 py-6">
                            @if(in_array(auth()->user()->role, ['admin', 'sales_manager', 'sales_rep']))
                                <form action="{{ route('orders.update', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                        class="text-[9px] font-black uppercase tracking-widest border border-slate-200 bg-white py-1 px-3 focus:ring-1 focus:ring-[#AEA181] outline-none cursor-pointer transition-all hover:border-[#AEA181]">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            @else
                                <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1 border 
                                    {{ $order->status == 'completed' ? 'border-green-200 text-green-600 bg-green-50' : '' }}
                                    {{ $order->status == 'processing' ? 'border-[#AEA181]/30 text-[#AEA181] bg-[#AEA181]/5' : '' }}
                                    {{ $order->status == 'pending' ? 'border-slate-200 text-slate-400 bg-slate-50' : '' }}
                                    {{ $order->status == 'cancelled' ? 'border-red-200 text-red-600 bg-red-50' : '' }}">
                                    {{ $order->status }}
                                </span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-8 py-6 text-right">
                            <span class="text-[10px] text-slate-400 font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                            <p class="text-[8px] text-slate-300 font-mono">{{ $order->created_at->format('H:i') }} PST</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center">
                            <i class="fas fa-file-invoice text-slate-100 text-5xl mb-4"></i>
                            <p class="text-[10px] uppercase tracking-[0.4em] text-slate-300 font-black">Ledger is currently empty</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if(method_exists($orders, 'links'))
            <div class="p-8 border-t border-slate-50 bg-[#FDFCFB]/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection