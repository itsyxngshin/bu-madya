<div class="min-h-screen bg-gray-100 font-sans text-gray-900">

    {{-- ADMIN HEADER --}}
    <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.proposals.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800">Review Proposal <span class="text-gray-400 font-normal">#{{ $proposal->id }}</span></h1>
        </div>
        
        {{-- Status Badge (Dynamic) --}}
        <div>
            @if($proposal->status === 'approved')
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-black uppercase tracking-widest rounded-full">Approved</span>
            @elseif($proposal->status === 'rejected')
                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-black uppercase tracking-widest rounded-full">Rejected</span>
            @elseif($proposal->status === 'returned')
                <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-black uppercase tracking-widest rounded-full">Returned</span>
            @else
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-black uppercase tracking-widest rounded-full">Pending Review</span>
            @endif
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8 grid lg:grid-cols-12 gap-8">

        {{-- LEFT COLUMN: CONTENT (Reading Pane) --}}
        <main class="lg:col-span-8 space-y-6">
            
            {{-- Proponent Info --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-lg">
                        {{ substr($proposal->user ? $proposal->user->name : $proposal->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">{{ $proposal->user ? $proposal->user->name : $proposal->name }}</h2>
                        <p class="text-xs text-gray-500">{{ $proposal->user ? $proposal->user->email : $proposal->email }}</p>
                        <div class="flex gap-2 mt-2">
                            <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 rounded text-[10px] uppercase font-bold text-gray-600">{{ $proposal->proponent_type }}</span>
                            @if($proposal->college)
                                <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 rounded text-[10px] uppercase font-bold text-gray-600">{{ $proposal->college->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Submitted On</p>
                    <p class="text-xs font-bold text-gray-700">{{ $proposal->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            {{-- The Proposal Body --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-bold uppercase rounded mb-4">{{ $proposal->project_type }}</span>
                
                <h2 class="text-3xl font-black font-heading text-gray-900 mb-6">{{ $proposal->title }}</h2>
                
                <div class="prose prose-sm max-w-none text-gray-600 mb-8">
                    <h4 class="text-xs font-bold text-gray-900 uppercase mb-2">Rationale</h4>
                    <p class="whitespace-pre-line leading-relaxed">{{ $proposal->rationale }}</p>
                </div>

                <div class="bg-stone-50 rounded-xl p-6 border border-stone-100 mb-8">
                    <h4 class="text-xs font-bold text-gray-900 uppercase mb-4">Objectives</h4>
                    <ul class="space-y-2">
                        @foreach($proposal->objectives as $obj)
                            <li class="flex items-start gap-3 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $obj->objective }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Target Date</span>
                        <span class="font-bold text-gray-900">
                            {{ $proposal->target_start_date?->format('M d') }} - {{ $proposal->target_end_date?->format('M d, Y') }}
                        </span>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Beneficiaries</span>
                        <span class="font-bold text-gray-900">{{ $proposal->target_beneficiaries }}</span>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Venue ({{ ucfirst($proposal->modality) }})</span>
                        <span class="font-bold text-gray-900">{{ $proposal->venue }}</span>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Estimated Budget</span>
                        <span class="font-bold text-gray-900 font-mono">₱{{ number_format($proposal->estimated_budget, 2) }}</span>
                    </div>
                </div>
            </div>

        </main>

        {{-- RIGHT COLUMN: ACTIONS (Sticky) --}}
        <aside class="lg:col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    Admin Action
                </h3>

                {{-- Remarks Input --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Feedback / Remarks</label>
                    <textarea wire:model="admin_remarks" rows="4" 
                        class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400 bg-gray-50"
                        placeholder="Write feedback for the proponent here..."></textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-2">
                    <button wire:click="updateStatus('approved')" 
                        class="w-full py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold uppercase rounded-lg transition shadow-sm hover:shadow-md">
                        Approve Proposal
                    </button>
                    
                    <button wire:click="updateStatus('returned')" 
                        class="w-full py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 text-xs font-bold uppercase rounded-lg transition shadow-sm hover:shadow-md">
                        Return for Revision
                    </button>
                    
                    <button wire:click="updateStatus('rejected')" 
                        class="w-full py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 text-xs font-bold uppercase rounded-lg transition">
                        Reject
                    </button>
                </div>

                {{-- Helper Text --}}
                <p class="text-[10px] text-gray-400 mt-4 text-center">
                    Updating the status will notify the proponent via email if provided.
                </p>
            </div>
        </aside>

    </div>
</div>