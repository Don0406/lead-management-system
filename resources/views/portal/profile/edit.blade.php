@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-10" data-aos="fade-up">
    
    {{-- NAVIGATION HUB --}}
    <div class="flex flex-wrap gap-8 mb-12 border-b border-slate-100 pb-2">
        <a href="{{ route('dashboard') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-[#5A4651] transition-all pb-6">
            Project Overview
        </a>
        {{-- This route is now defined in web.php, so it won't crash anymore --}}
        <a href="{{ route('orders.index') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-[#5A4651] transition-all pb-6">
            My Orders
        </a>
        <a href="{{ route('profile.edit') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-[#5A4651] border-b-2 border-[#AEA181] pb-6">
            Security Profile
        </a>
    </div>

    {{-- HEADER --}}
    <div class="text-center space-y-4 mb-12">
        <span class="text-[10px] font-black uppercase tracking-[0.6em] text-[#AEA181] block">Identity Management</span>
        <h2 class="brand-font text-5xl text-[#5A4651] italic">Security Profile<span class="text-[#AEA181]">.</span></h2>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 border border-green-100 p-4 text-center text-[10px] font-black uppercase tracking-widest text-green-600 mb-6">
            Security credentials and identity image updated successfully.
        </div>
    @endif

    <div class="bg-white border border-slate-100 p-12">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-12">
            @csrf
            @method('patch')

            {{-- AVATAR UPLOAD --}}
            <div class="flex items-center gap-10 pb-10 border-b border-slate-50">
                <div class="relative">
                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=5A4651&color=fff' }}" 
                         class="w-28 h-28 rounded-full object-cover border-2 border-[#AEA181] p-1 shadow-sm">
                </div>
                <div class="space-y-3">
                    <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block">Profile Identification Image</label>
                    <input type="file" name="avatar" class="text-[10px] text-slate-400 file:mr-4 file:py-2 file:px-4 file:border file:border-slate-100 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-[#5A4651] hover:file:bg-[#5A4651] hover:file:text-white transition-all">
                </div>
            </div>

            {{-- PASSWORD UPDATE --}}
            <div class="space-y-8 pt-4">
                <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-[#5A4651]">Security Access Key Update</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-2">
                        <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block">New Password</label>
                        <input type="password" name="new_password" placeholder="••••••••" class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] bg-transparent pb-2">
                        @error('new_password') <p class="text-[9px] text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[8px] uppercase font-black text-slate-300 tracking-[0.2em] block">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" placeholder="••••••••" class="w-full border-0 border-b border-slate-100 focus:ring-0 focus:border-[#AEA181] bg-transparent pb-2">
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="bg-[#5A4651] hover:bg-[#AEA181] text-white px-12 py-4 text-[9px] font-black uppercase tracking-[0.4em] transition-all shadow-lg">
                    Authorize Security Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection