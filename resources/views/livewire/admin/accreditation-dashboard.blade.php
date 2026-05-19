<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8">

    {{-- HEADER & STATS --}}
    <div>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">OSAS Command Center</h1>
        <p class="text-sm text-gray-500 mt-1">Manage accreditations, set deadlines, and broadcast advisories.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending Review</p>
                <p class="text-4xl font-black text-orange-500">{{ $pendingApps }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Approved Orgs</p>
                <p class="text-4xl font-black text-green-500">{{ $approvedApps }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Registered</p>
                <p class="text-4xl font-black text-blue-500">{{ $totalOrgs }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        {{-- LEFT COLUMN: APPLICATION QUEUE --}}
        <div class="xl:col-span-2 space-y-6">
            <h2 class="text-lg font-black text-gray-900 uppercase tracking-widest">Application Queue</h2>
            
            @if(session()->has('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-50 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($pendingList as $app)
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all hover:border-blue-300">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-1 text-[8px] font-black uppercase tracking-widest rounded bg-blue-50 text-blue-600">{{ $app->application_type }}</span>
                                    <span class="text-xs font-bold text-gray-400">Submitted: {{ $app->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-xl font-black text-gray-900">{{ $app->organization->name }}</h3>
                                <p class="text-sm text-gray-500">{{ ucfirst($app->organization->type) }}</p>
                            </div>
                            <button wire:click="$set('reviewingApplicationId', {{ $app->id }})" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-lg hover:bg-gray-800 transition-colors">
                                Review Files
                            </button>
                        </div>

                        {{-- Quick Review Panel (Expands when Review clicked) --}}
                        @if($reviewingApplicationId === $app->id)
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Administrator Action</p>
                                <textarea wire:model="adminRemarks" placeholder="Add remarks (Required if returning the application to the student)..." class="w-full text-sm rounded-lg border-gray-200 mb-3 bg-gray-50" rows="3"></textarea>
                                @error('adminRemarks') <span class="text-red-500 text-xs block mb-3">{{ $message }}</span> @enderror
                                
                                <div class="flex gap-3">
                                    <button wire:click="updateApplicationStatus({{ $app->id }}, 'approved')" class="px-6 py-2 bg-green-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-green-700">Approve</button>
                                    <button wire:click="updateApplicationStatus({{ $app->id }}, 'returned')" class="px-6 py-2 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-red-700">Return for Correction</button>
                                    <button wire:click="$set('reviewingApplicationId', null)" class="px-6 py-2 bg-gray-100 text-gray-600 text-xs font-black uppercase tracking-widest rounded-lg hover:bg-gray-200">Cancel</button>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No pending applications</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: CONTROL CENTER (Deadlines & Advisories) --}}
        <div class="space-y-8">
            
            {{-- Broadcast Advisory --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Broadcast Advisory
                </h2>
                <form wire:submit.prevent="postAdvisory" class="space-y-3">
                    <input type="text" wire:model="adv_title" placeholder="Advisory Title" class="w-full text-sm rounded-lg border-gray-200 bg-gray-50">
                    <select wire:model="adv_type" class="w-full text-sm rounded-lg border-gray-200 bg-gray-50">
                        <option value="info">General Info (Blue)</option>
                        <option value="warning">Warning (Yellow)</option>
                        <option value="urgent">Urgent/Strict (Red)</option>
                    </select>
                    <textarea wire:model="adv_message" placeholder="Type your announcement here..." rows="3" class="w-full text-sm rounded-lg border-gray-200 bg-gray-50 resize-none"></textarea>
                    <button type="submit" class="w-full py-2.5 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-colors">Post Announcement</button>
                </form>
            </div>

            {{-- Set Deadlines --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Set Submission Deadlines
                </h2>
                <form wire:submit.prevent="setDeadline" class="space-y-3">
                    <select wire:model="dl_academic_year_id" class="w-full text-sm rounded-lg border-gray-200 bg-gray-50">
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}">{{ $ay->year }}</option>
                        @endforeach
                    </select>
                    <select wire:model="dl_type" class="w-full text-sm rounded-lg border-gray-200 bg-gray-50">
                        <option value="both">Both (Accreditation & Reaccreditation)</option>
                        <option value="accreditation">New Accreditation Only</option>
                        <option value="reaccreditation">Reaccreditation Only</option>
                    </select>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Opens On</label>
                            <input type="datetime-local" wire:model="dl_start" class="w-full text-xs rounded-lg border-gray-200 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Closes On</label>
                            <input type="datetime-local" wire:model="dl_end" class="w-full text-xs rounded-lg border-gray-200 bg-gray-50">
                        </div>
                    </div>
                    @error('dl_end') <span class="text-red-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    <button type="submit" class="w-full py-2.5 bg-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-black transition-colors mt-2">Activate Deadline</button>
                </form>

                {{-- Active Deadlines List --}}
                @if($activeDeadlines->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Currently Active</p>
                        <div class="space-y-2">
                            @foreach($activeDeadlines as $dl)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-xs">
                                    <p class="font-bold text-gray-900">{{ $dl->academicYear->year }} <span class="text-gray-500 font-normal">({{ ucfirst($dl->application_type) }})</span></p>
                                    <p class="text-red-600 font-mono mt-1">Due: {{ \Carbon\Carbon::parse($dl->end_date)->format('M d, Y h:i A') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>