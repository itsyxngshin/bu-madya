<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-900">

    {{-- 1. HEADER BANNER --}}
    <div class="relative h-48 md:h-64 w-full overflow-hidden bg-gray-800">
        {{-- Gradient & Pattern --}}
        <div class="absolute inset-0 bg-gradient-to-r from-red-600 via-orange-500 to-green-600 opacity-90"></div>
        <div class="absolute inset-0 opacity-20" 
             style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M20 38v-2h2v2h-2zm0-36V0h2v2h-2zM0 20v-2h2v2H0zm38 0v-2h2v2h-2z\'/%3E%3Cpath d=\'M19 19h2v2h-2z\'/%3E%3C/g%3E%3C/svg%3E');">
        </div>
        
        {{-- Title Content --}}
        <div class="absolute bottom-0 left-0 w-full p-8 md:p-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] font-bold uppercase tracking-widest">
                        Portal
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">My Evaluations</h1>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 -mt-8">
        
        {{-- SECTION A: PENDING EVALUATIONS --}}
        <div class="mb-12">
            <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span> Pending Action
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pending as $eval)
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-gray-100 relative group overflow-hidden hover:-translate-y-1 transition duration-300">
                        {{-- Top Stripe --}}
                        <div class="absolute top-0 left-0 w-full h-2 bg-yellow-400"></div>
                        
                        <div class="flex justify-between items-start mb-4 mt-2">
                            {{-- Icon --}}
                            <div class="w-12 h-12 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center border border-yellow-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-[10px] font-bold uppercase tracking-wide">
                                Required
                            </span>
                        </div>

                        <h3 class="text-xl font-black text-gray-900 mb-2 leading-tight">{{ $eval->title }}</h3>
                        <p class="text-sm text-gray-500 mb-6 line-clamp-2 leading-relaxed">
                            {{ $eval->description ?? 'Please complete this evaluation to help us improve future activities.' }}
                        </p>

                        <div class="flex items-center justify-between border-t border-gray-50 pt-4">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                                {{ $eval->created_at->format('M d, Y') }}
                            </span>
                            
                            @if($eval->google_form_url)
                                {{-- External Link --}}
                                <a href="{{ $eval->google_form_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-gray-900 hover:text-red-600 transition">
                                    Open Form <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @else
                                {{-- Internal Link --}}
                                <a href="{{ route('evaluations.show', $eval->id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-900 hover:text-red-600 transition">
                                    Start Now <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-[2rem] border-2 border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">All Caught Up!</h3>
                        <p class="text-gray-500 text-sm">You have no pending evaluations at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SECTION B: COMPLETED HISTORY --}}
        @if($completed->count() > 0)
        <div>
            <h2 class="text-lg font-bold text-gray-400 mb-6 uppercase tracking-widest text-xs">Completed History</h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-80 hover:opacity-100 transition duration-500">
                @foreach($completed as $eval)
                    <div class="bg-gray-50 rounded-[2rem] p-6 border border-gray-200">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-white text-green-600 flex items-center justify-center border border-gray-200 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-wide">
                                Completed
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-700 mb-2">{{ $eval->title }}</h3>
                        <p class="text-sm text-gray-500 mb-6 line-clamp-1">Response submitted.</p>

                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                                View Details
                            </span>
                            <button disabled class="text-gray-300 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>