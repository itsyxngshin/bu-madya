<div class="max-w-7xl mx-auto py-8 px-4 font-sans pb-32">
    
    {{-- HEADER & STATS --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Voter Turnout Logs
            </h1>
            <p class="text-gray-500 font-medium ml-9">Monitoring live turnout for: <span class="font-bold">{{ $election->title }}</span></p>
        </div>

        <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Verified Ballots</p>
                <p class="text-2xl font-black text-gray-900 leading-none">{{ number_format($totalTurnout) }}</p>
            </div>
        </div>
    </div>

    {{-- FILTERS & SEARCH --}}
    <div class="bg-white rounded-t-3xl shadow-sm border border-gray-200 border-b-0 p-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-blue-500 transition-colors" placeholder="Search by name, email, or program...">
        </div>
        
        <div class="md:w-64 shrink-0">
            <select wire:model.live="filterType" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-blue-500 transition-colors">
                <option value="all">All Voters</option>
                <option value="registered">Registered Members Only</option>
                <option value="guest">Guest Voters Only</option>
            </select>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-b-3xl shadow-sm border border-gray-200 overflow-hidden relative">
        
        {{-- Loading Overlay --}}
        <div wire:loading.delay wire:target="search, filterType, gotoPage, nextPage, previousPage" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-200">
                        <th class="p-5">Voter Identity</th>
                        <th class="p-5">Account Type</th>
                        <th class="p-5">Academic Profile</th>
                        <th class="p-5 text-right">Timestamp (PST)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-5">
                                <p class="font-black text-gray-900 text-sm">
                                    {{ $log->user_id ? $log->user->name : $log->guest_name }}
                                </p>
                                <p class="text-xs font-bold text-gray-500 mt-0.5">
                                    {{ $log->user_id ? $log->user->email : $log->guest_email }}
                                </p>
                            </td>
                            <td class="p-5">
                                @if($log->user_id)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 text-[10px] uppercase font-black tracking-widest rounded-md border border-blue-100">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Registered
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-50 text-orange-700 text-[10px] uppercase font-black tracking-widest rounded-md border border-orange-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Guest
                                    </span>
                                @endif
                            </td>
                            <td class="p-5">
                                <p class="font-bold text-gray-800 text-sm truncate max-w-[250px]">
                                    {{ $log->user_id ? ($log->user->college->name ?? 'N/A') : ($log->college->name ?? 'N/A') }}
                                </p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">
                                    {{ $log->user_id ? $log->user->program : $log->program }} 
                                    ({{ $log->user_id ? $log->user->year_level : $log->year_level }})
                                </p>
                            </td>
                            <td class="p-5 text-right">
                                <p class="font-bold text-gray-900 text-sm">{{ $log->voted_at->format('M d, Y') }}</p>
                                <p class="text-xs font-bold text-gray-500 mt-0.5">{{ $log->voted_at->format('h:i:s A') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </div>
                                <p class="text-gray-400 text-sm font-bold">No voter logs found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>