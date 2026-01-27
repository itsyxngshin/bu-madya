<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-900">
    
    {{-- 1. PUBLIC HERO SECTION --}}
    <div class="bg-gray-900 h-64 relative overflow-hidden flex flex-col justify-center px-6 lg:px-12">
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-red-600 to-orange-500 rounded-full blur-3xl opacity-20 -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-600 rounded-full blur-3xl opacity-10 -ml-10 -mb-10"></div>
        
        <div class="relative z-10 max-w-5xl mx-auto w-full">
            <span class="text-orange-500 font-bold tracking-widest uppercase text-xs mb-2 block">Feedback Portal</span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-2">
                Open Evaluations
            </h1>
            <p class="text-gray-400 text-sm md:text-base max-w-2xl">
                Share your thoughts and help us improve. Select an active evaluation form below to get started.
            </p>
        </div>
    </div>

    {{-- 2. CARDS GRID --}}
    <div class="max-w-5xl mx-auto px-6 -mt-16 relative z-20">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($evaluations as $eval)
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col h-full hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                    
                    {{-- Status Color Bar (Top) --}}
                    <div class="absolute top-0 left-0 w-full h-1.5 {{ $eval->status === 'Completed' ? 'bg-green-500' : 'bg-orange-500' }}"></div>

                    {{-- Card Header --}}
                    <div class="mb-4 mt-2 flex justify-between items-start">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border
                            {{ $eval->status === 'Completed' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                            {{ $eval->status }}
                        </span>
                        
                        {{-- Project Label (Optional, if linked) --}}
                        @if($eval->project)
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                Project
                            </span>
                        @endif
                    </div>

                    {{-- Title & Desc --}}
                    <h3 class="text-xl font-black text-gray-900 mb-2 leading-tight group-hover:text-orange-600 transition-colors">
                        {{ $eval->title }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-6 line-clamp-3 flex-grow">
                        {{ $eval->description ?: 'No description provided.' }}
                    </p>

                    {{-- Action Footer --}}
                    <div class="pt-4 mt-auto border-t border-gray-50">
                        @if($eval->status === 'Pending')
                            {{-- Start Button --}}
                            <a href="{{ route('evaluations.show', $eval) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-gray-900 text-white font-bold rounded-xl shadow-md hover:bg-orange-600 transition-colors text-xs uppercase tracking-wider">
                                Start Now 
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        @else
                            {{-- Submitted State --}}
                            <button disabled class="w-full flex items-center justify-center gap-2 py-3 bg-green-50 text-green-600 font-bold rounded-xl border border-green-100 cursor-default text-xs uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Response Submitted
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No Active Evaluations</h3>
                    <p class="text-gray-500 text-sm mt-1">Check back later for new feedback forms.</p>
                </div>
            @endforelse

        </div>
        
        {{-- 3. PAGINATION LINKS --}}
        <div class="mt-12">
            {{ $evaluations->links() }}
        </div>

    </div>
</div>