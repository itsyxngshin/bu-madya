<div class="p-6 max-w-7xl mx-auto space-y-8 animate-fade-in-up">
    
    {{-- Welcome Header --}}
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-3xl p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/10 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        
        <div class="relative z-10">
            <span class="inline-block px-3 py-1 bg-red-500/20 text-red-400 text-[10px] font-black uppercase tracking-widest rounded-full mb-3 border border-red-500/30">Partner Portal</span>
            <h1 class="text-3xl md:text-5xl font-black mb-2">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-gray-400 font-medium">Manage your organization's events, track attendance, and launch campaigns.</p>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Events Hosted</p>
                <p class="text-2xl font-black text-gray-900">{{ $stats['events'] }}</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Total Checked-in</p>
                <p class="text-2xl font-black text-gray-900">{{ $stats['attendees'] }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Frames Submitted</p>
                <p class="text-2xl font-black text-gray-900">{{ $stats['frames'] }}</p>
            </div>
        </div>
    </div>

    {{-- Action Row --}}
    <div class="flex gap-4">
        <a href="{{ route('admin.events.create') }}" class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-red-700 transition shadow-md">
            + Host New Event
        </a>
        <a href="{{ route('partner.frames.submit') }}" class="bg-white text-gray-700 border border-gray-200 px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-50 transition shadow-sm">
            Submit Campaign Frame
        </a>
    </div>
</div>