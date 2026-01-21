@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-12" data-aos="fade-up">
    
    {{-- Header --}}
    <div class="flex justify-between items-end border-b border-slate-100 pb-8">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block mb-2">Personnel Management</span>
            <h2 class="brand-font text-4xl text-[#5A4651] italic">Provision Staff<span class="text-[#AEA181]">.</span></h2>
        </div>
        <div class="text-right">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Active Personnel</span>
            <p class="brand-font text-2xl text-[#AEA181]">{{ $staff->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {{-- Column 1: The Provisioning Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-100 p-8 sticky top-32 shadow-sm">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-[#5A4651] mb-6 border-b border-slate-50 pb-4">New Account Protocol</h4>
                
                <form action="{{ route('admin.provisionStaff') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="text-[8px] font-black uppercase text-slate-400 block mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic outline-none focus:border-[#AEA181] @error('name') border-red-300 @enderror" required>
                        @error('name') <p class="text-[9px] text-red-500 mt-1 uppercase font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[8px] font-black uppercase text-slate-400 block mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic outline-none focus:border-[#AEA181] @error('email') border-red-300 @enderror" required>
                        @error('email') <p class="text-[9px] text-red-500 mt-1 uppercase font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[8px] font-black uppercase text-slate-400 block mb-2">Temporary Password</label>
                        <input type="password" name="password" class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-sm italic outline-none focus:border-[#AEA181] @error('password') border-red-300 @enderror" required>
                        @error('password') <p class="text-[9px] text-red-500 mt-1 uppercase font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[8px] font-black uppercase text-slate-400 block mb-2">Assigned Role</label>
                        <select name="role" class="w-full bg-[#FDFCFB] border border-slate-100 p-3 text-[10px] font-bold uppercase outline-none focus:border-[#AEA181]">
                            <option value="sales_rep">Sales Representative</option>
                            <option value="sales_manager">Sales Manager</option>
                            <option value="admin">System Administrator</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-[#5A4651] text-white text-[9px] font-black uppercase tracking-widest hover:bg-[#3d3037] transition-all shadow-lg shadow-slate-100">
                        Initialize Account
                    </button>
                </form>
            </div>
        </div>

        {{-- Column 2: Existing Staff Registry --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-100 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#FDFCFB] border-b border-slate-50">
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Personnel</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Role</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($staff as $member)
                        <tr x-data="{ editing: false }" class="group hover:bg-[#FDFCFB]/50 transition-colors">
                            {{-- View State --}}
                            <template x-if="!editing">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-[#5A4651]">{{ $member->name }}</p>
                                    <p class="text-[10px] text-slate-400 tracking-wider">{{ $member->email }}</p>
                                </td>
                            </template>
                            <template x-if="!editing">
                                <td class="px-6 py-4">
                                    <span class="text-[8px] font-black uppercase px-2 py-1 border border-slate-100 text-slate-500 bg-white">
                                        {{ str_replace('_', ' ', $member->role) }}
                                    </span>
                                </td>
                            </template>

                            {{-- Edit State --}}
                            <template x-if="editing">
                                <td colspan="2" class="px-6 py-4">
                                    <form id="edit-form-{{ $member->id }}" action="{{ route('admin.updateStaff', $member->id) }}" method="POST" class="flex gap-4">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $member->name }}" class="text-xs border border-slate-200 p-1 outline-none focus:border-[#AEA181]">
                                        <select name="role" class="text-[9px] font-bold uppercase border border-slate-200 outline-none">
                                            <option value="sales_rep" {{ $member->role == 'sales_rep' ? 'selected' : '' }}>Rep</option>
                                            <option value="sales_manager" {{ $member->role == 'sales_manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="admin" {{ $member->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                            </template>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-4">
                                    {{-- Edit Toggle --}}
                                    <button @click="editing = !editing" x-text="editing ? 'CANCEL' : 'EDIT'" class="text-[8px] font-black tracking-widest text-[#AEA181] hover:text-[#5A4651] transition-colors">
                                        EDIT
                                    </button>

                                    {{-- Save Button (Visible only when editing) --}}
                                    <button x-show="editing" form="edit-form-{{ $member->id }}" type="submit" class="text-[8px] font-black tracking-widest text-green-600">
                                        SAVE
                                    </button>

                                    {{-- Delete --}}
                                    <form x-show="!editing" action="{{ route('admin.destroyStaff', $member->id) }}" method="POST" onsubmit="return confirm('Purge this account from the registry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection