<div class="max-w-7xl mx-auto space-y-6 pb-24">

    <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7]">
        <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">System Overview</h1>
        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Live metrics and rapid action center.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-iba-red/10 border-l-4 border-iba-red p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-red uppercase tracking-wider">{{ session('error') }}</p>
        </div>
    @endif

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#0095AC]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Total Intake</dt>
            <dd class="text-4xl font-pixel text-iba-black dark:text-white">{{ $stats['total'] }}</dd>
        </div>
        <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#FF8623]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Awaiting Review</dt>
            <dd class="text-4xl font-pixel text-iba-orange dark:text-iba-orange">{{ $stats['pending'] }}</dd>
        </div>
        <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#10B981]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Approved</dt>
            <dd class="text-4xl font-pixel text-iba-green">{{ $stats['approved'] }}</dd>
        </div>
        <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#EF4444]">
            <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Rejected</dt>
            <dd class="text-4xl font-pixel text-iba-red">{{ $stats['rejected'] }}</dd>
        </div>
    </div>

    {{-- QUICK ACTIONS & SYSTEM CONTROLS --}}
    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-8 shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7]">
        <h2 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-6">Quick Directives & Controls</h2>

        <div class="flex flex-col sm:flex-row flex-wrap gap-4 items-start sm:items-center justify-between border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-8 mb-8">

            {{-- Standard Directives --}}
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('ibalong.admin.registrants') }}" class="bg-iba-teal text-white font-bold px-6 py-2.5 text-sm uppercase border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                    Manage Registrants
                </a>
                <a href="#" class="bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold px-6 py-2.5 text-sm uppercase border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                    Export Data (CSV)
                </a>
            </div>

            {{-- MASTER REGISTRATION TOGGLE (Hidden from Facilitators) --}}
            @if(in_array(auth('ibalong')->user()->role_id, [1, 2]))
                <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-900 p-4 border-4 border-iba-black dark:border-iba-light w-full sm:w-auto mt-4 sm:mt-0">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">Portal Status</p>
                        <p class="text-sm font-black uppercase tracking-wider {{ $isRegistrationOpen ? 'text-iba-green' : 'text-iba-red' }}">
                            {{ $isRegistrationOpen ? 'ACCEPTING APPLICANTS' : 'INTAKE LOCKED' }}
                        </p>
                    </div>

                    <button wire:click="toggleRegistration" class="ml-auto font-bold px-6 py-2 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1 {{ $isRegistrationOpen ? 'bg-iba-red text-white' : 'bg-iba-green text-white dark:border-iba-light' }}">
                        {{ $isRegistrationOpen ? 'LOCK PORTAL' : 'OPEN PORTAL' }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
