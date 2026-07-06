<div class="max-w-7xl mx-auto animate-fade-in-up">
    
    <div class="mb-8">
        <h1 class="font-pixel text-2xl sm:text-3xl text-iba-black dark:text-iba-light uppercase tracking-wide">SYSTEM OVERVIEW</h1>
        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-2">Welcome to the BU MADYA Command Center</p>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        
        {{-- Total Cohorts --}}
        <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#0095AC]">
            <h3 class="font-pixel text-[10px] text-gray-500 uppercase tracking-widest mb-4">Total Intake</h3>
            <div class="text-4xl font-pixel text-iba-teal">{{ $stats['total'] }}</div>
        </div>

        {{-- Pending --}}
        <div class="bg-iba-orange p-6 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
            <h3 class="font-pixel text-[10px] text-iba-black uppercase tracking-widest mb-4">Awaiting Review</h3>
            <div class="text-4xl font-pixel text-iba-black">{{ $stats['pending'] }}</div>
        </div>

        {{-- Approved --}}
        <div class="bg-iba-green p-6 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
            <h3 class="font-pixel text-[10px] text-white uppercase tracking-widest mb-4">Approved Cohorts</h3>
            <div class="text-4xl font-pixel text-white">{{ $stats['approved'] }}</div>
        </div>

        {{-- Rejected --}}
        <div class="bg-iba-red p-6 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
            <h3 class="font-pixel text-[10px] text-white uppercase tracking-widest mb-4">Rejected</h3>
            <div class="text-4xl font-pixel text-white">{{ $stats['rejected'] }}</div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-8 shadow-[8px_8px_0_0_#CF452C]">
        <h2 class="font-pixel text-lg text-iba-black dark:text-iba-light mb-6">QUICK DIRECTIVES</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('ibalong.admin.registrants') }}" class="btn-retro bg-iba-teal text-white font-pixel px-6 py-4 text-xs uppercase flex items-center gap-2">
                Manage Registrants ➔
            </a>
            <a href="#" class="btn-retro bg-iba-light dark:bg-iba-black text-iba-black dark:text-iba-light font-pixel px-6 py-4 text-xs uppercase flex items-center gap-2">
                Export Data (CSV)
            </a>
        </div>
    </div>
</div>