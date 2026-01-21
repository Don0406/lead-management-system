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
            <tr class="border-b border-slate-50 bg-[#FDFCFB]">
                <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Reference</th>
                @if(auth()->user()->role !== 'client')
                    <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Principal (Client)</th>
                @endif
                <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Asset / Service</th>
                <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Status Management</th>
                <th class="p-8 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Investment</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($orders as $order)
                <tr class="hover:bg-[#FDFCFB]/50 transition-colors">
                    <td class="p-8 text-xs font-mono text-slate-400">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                    
                    {{-- STAFF ONLY: Show who the client is --}}
                    @if(auth()->user()->role !== 'client')
                    <td class="p-8">
                        <p class="text-[11px] font-bold text-[#5A4651]">{{ $order->user->name ?? 'Unknown User' }}</p>
                        <p class="text-[9px] text-slate-400 tracking-tighter">{{ $order->user->email }}</p>
                    </td>
                    @endif

                    <td class="p-8">
                        <p class="text-sm font-bold text-[#5A4651] uppercase tracking-widest">{{ $order->product->name ?? 'Custom Service' }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 italic">{{ $order->created_at->format('M d, Y') }}</p>
                    </td>

                    <td class="p-8">
                        @if(auth()->user()->role === 'client')
                            {{-- Client just sees a label --}}
                            <span class="px-3 py-1 text-[8px] font-black uppercase tracking-widest {{ $order->status == 'completed' ? 'bg-green-50 text-green-600' : 'bg-[#AEA181]/10 text-[#AEA181]' }}">
                                {{ $order->status }}
                            </span>
                        @else
                            {{-- Staff sees an update form --}}
                            <form action="{{ route('orders.update', $order) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-[8px] font-black uppercase tracking-widest border-slate-100 bg-transparent py-1 px-2 focus:ring-0 focus:border-[#AEA181]">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        @endif
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