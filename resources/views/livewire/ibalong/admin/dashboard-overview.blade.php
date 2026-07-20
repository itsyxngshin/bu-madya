<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="bg-white dark:bg-[#1A1617] p-6 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">System Overview</h1>
        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Live metrics and rapid action center.</p>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        
        <div class="bg-white dark:bg-[#1A1617] p-5 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#0095AC]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Total Intake</dt>
            <dd class="text-4xl font-pixel text-iba-black dark:text-white">{{ $stats['total'] }}</dd>
        </div>

        <div class="bg-white dark:bg-[#1A1617] p-5 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#FF8623]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Awaiting Review</dt>
            <dd class="text-4xl font-pixel text-iba-orange dark:text-iba-orange">{{ $stats['pending'] }}</dd>
        </div>

        <div class="bg-white dark:bg-[#1A1617] p-5 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#10B981]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Approved</dt>
            <dd class="text-4xl font-pixel text-iba-green">{{ $stats['approved'] }}</dd>
        </div>

        <div class="bg-white dark:bg-[#1A1617] p-5 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#EF4444]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Rejected</dt>
            <dd class="text-4xl font-pixel text-iba-red">{{ $stats['rejected'] }}</dd>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light p-6 sm:p-8 shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
        <h2 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-6">Quick Directives</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('ibalong.admin.registrants') }}" class="bg-iba-teal text-white font-bold px-6 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                Manage Registrants
            </a>
            <a href="#" class="bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold px-6 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                Export Data (CSV)
            </a>
        </div>
    </div>
</div>