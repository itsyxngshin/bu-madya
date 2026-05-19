<div class="mt-6 animate-fade-in-up">
    
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 text-sm font-bold rounded-2xl border border-green-100 shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-12 gap-8 items-start">
        
        {{-- LEFT COLUMN: THE JOURNAL FEED --}}
        <div class="lg:col-span-8 bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">OJT Journal</h2>
                <button wire:click="createNewEntry" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-widest rounded-xl transition-all text-[10px] md:text-xs shadow-md shadow-red-600/20 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Entry
                </button>
            </div>

            {{-- Entries List (Grouped by Week Accordions) --}}
            <div class="space-y-4">
                @forelse($weeklyData as $weekKey => $data)
                    <div x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }" class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm transition-all hover:border-gray-200">
                        
                        {{-- Accordion Header --}}
                        <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-5 bg-gray-50/50 hover:bg-gray-50 transition-colors focus:outline-none">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-left">
                                <h3 class="text-[11px] md:text-xs font-black text-red-600 uppercase tracking-widest bg-red-50 px-2 py-1 rounded">Week {{ $data['week_number'] }}</h3>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $data['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-[9px] font-bold text-gray-500 bg-white px-2.5 py-1 rounded-md border border-gray-200 shadow-sm hidden sm:block">{{ count($data['blogs']) }} {{ count($data['blogs']) === 1 ? 'Entry' : 'Entries' }}</span>
                                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </button>

                        {{-- Accordion Body --}}
                        <div x-show="expanded" x-collapse>
                            <div class="p-5 space-y-4 border-t border-gray-100 bg-white">
                                @if(count($data['blogs']) > 0)
                                    @foreach($data['blogs'] as $blog)
                                        <div class="p-5 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors group relative shadow-sm hover:shadow-md">
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-center gap-3">
                                                    <h3 class="font-bold text-gray-900 text-base md:text-lg tracking-tight">{{ $blog->title }}</h3>
                                                    <button wire:click="editBlog({{ $blog->id }})" class="text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity p-1.5 bg-white rounded-lg shadow-sm border border-gray-200" title="Edit Entry">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </button>
                                                </div>
                                                <span class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 bg-gray-100 text-gray-500 rounded-md shrink-0 border border-gray-200/60">
                                                    {{ str_replace('_', ' ', $blog->type) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center gap-3 text-[10px] md:text-xs text-gray-500 mb-4 font-bold uppercase tracking-wide">
                                                <span>{{ \Carbon\Carbon::parse($blog->report_date)->format('M d, Y') }}</span>
                                            </div>

                                            {{-- READ MORE / READ LESS TOGGLE --}}
                                            <div x-data="{ expandedText: false }" class="mb-4">
                                                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line transition-all" 
                                                   :class="expandedText ? '' : 'line-clamp-3'">
                                                    {{ $blog->content }}
                                                </p>
                                                
                                                @if(strlen($blog->content) > 150)
                                                    <button @click="expandedText = !expandedText" 
                                                            class="text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-widest mt-2 focus:outline-none transition-colors">
                                                        <span x-text="expandedText ? 'Show Less' : 'Read More'"></span>
                                                    </button>
                                                @endif
                                            </div>

                                            @if($blog->attachment_path)
                                                <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-100">
                                                    <a href="{{ asset('storage/' . $blog->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-blue-600 bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        View Photo Documentation
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No journal entries for this week.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">No journal entries yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: THE PROGRESS SIDEBAR --}}
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
            
            <div class="bg-gray-900 rounded-[2rem] p-6 md:p-8 text-white shadow-xl relative overflow-hidden flex flex-col h-[80vh] max-h-[800px]">
                {{-- Decorative Glows --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/20 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl -ml-10 -mb-10 pointer-events-none"></div>

                {{-- Header & Progress Bar --}}
                <div class="relative z-10 shrink-0">
                    <h3 class="font-heading text-2xl font-black mb-1 tracking-tight">Time Tracker</h3>
                    <p class="text-xs text-gray-400 mb-6 font-medium">Your Practicum Progress</p>

                    <div class="mb-6">
                        <div class="flex items-end justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Logged</span>
                            <div class="text-right">
                                <span class="text-4xl font-black font-mono text-white leading-none">{{ $totalAccumulated }}</span>
                                <span class="text-gray-400 text-xs">/ {{ $targetHours }}h</span>
                            </div>
                        </div>
                        
                        <div class="w-full h-3 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-600 to-yellow-500 transition-all duration-1000 ease-out rounded-full" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <p class="text-right text-[9px] text-gray-500 mt-2 font-bold">{{ number_format($progressPercentage, 1) }}% Completed</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6 border-t border-gray-800 pt-6">
                        <div>
                            <span class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Regular</span>
                            <span class="text-xl font-bold text-gray-200">{{ $regularHours }}h</span>
                        </div>
                        <div>
                            <span class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Overtime</span>
                            <span class="text-xl font-bold {{ $overtimeHours > 0 ? 'text-yellow-400' : 'text-gray-200' }}">{{ $overtimeHours }}h</span>
                        </div>
                    </div>
                </div>

                {{-- Scrollable Weekly Accordion Breakdown --}}
                <div class="relative z-10 flex-1 overflow-y-auto no-scrollbar border-t border-gray-800 pt-4 mt-2">
                    <div class="flex items-center justify-between mb-4 sticky top-0 bg-gray-900 pb-2 z-20">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Timesheet Logs</h4>
                        <span class="text-[9px] font-bold text-gray-500">Allow OT</span>
                    </div>

                    <div class="space-y-3 pb-4">
                        @forelse($weeklyData as $weekKey => $data)
                            <div x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }" class="bg-gray-800/40 rounded-xl border border-gray-700/50 overflow-hidden transition-colors">
                                
                                {{-- Accordion Header --}}
                                <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-3 focus:outline-none hover:bg-gray-800/60 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black text-white uppercase tracking-widest bg-red-600/80 px-1.5 py-0.5 rounded">W{{ $data['week_number'] }}</span>
                                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ $data['label'] }}</span>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                {{-- Accordion Body (Days) --}}
                                <div x-show="expanded" x-collapse>
                                    <div class="p-2 pt-0 space-y-1.5">
                                        @if(count($data['logs']) > 0)
                                            @foreach($data['logs'] as $dlog)
                                                <div class="flex items-center justify-between bg-gray-900/60 hover:bg-gray-800 transition px-3 py-2.5 rounded-lg border border-gray-700/30">
                                                    <div>
                                                        <p class="text-[11px] font-bold text-gray-200">{{ $dlog->date->format('M d') }} <span class="text-gray-500 font-normal ml-1">{{ $dlog->date->format('D') }}</span></p>
                                                        <p class="text-[9px] text-gray-400 mt-0.5 font-mono">
                                                            {{ $dlog->regular_hrs }}h Reg 
                                                            @if($dlog->overtime_hrs > 0 || $dlog->is_overtime_approved) 
                                                                <span class="{{ $dlog->is_overtime_approved ? 'text-yellow-500' : 'text-gray-500' }}">+ {{ $dlog->overtime_hrs }}h OT</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-[11px] font-black text-green-400 w-8 text-right">{{ $dlog->total_hrs }}h</span>
                                                        
                                                        {{-- Individual OT Toggle --}}
                                                        @if($dlog->potential_overtime_hrs > 0)
                                                            <button wire:click="toggleDailyOvertime({{ $dlog->id }})" class="relative inline-flex h-4 w-7 items-center rounded-full transition-colors focus:outline-none shrink-0 {{ $dlog->is_overtime_approved ? 'bg-red-500' : 'bg-gray-600' }}">
                                                                <span class="inline-block h-2.5 w-2.5 transform rounded-full bg-white transition-transform {{ $dlog->is_overtime_approved ? 'translate-x-3.5' : 'translate-x-1' }}"></span>
                                                            </button>
                                                        @else
                                                            <div class="w-7"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4 bg-gray-900/30 rounded-lg">
                                                <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">No DTR Logs</p>
                                            </div>
                                        @endif

                                        {{-- Sunday (No Work Day Badge) --}}
                                        <div class="flex items-center justify-between bg-red-900/10 px-3 py-2.5 rounded-lg border border-red-900/20">
                                            <div>
                                                <p class="text-[11px] font-bold text-red-400/80">{{ $data['sunday_date']->format('M d') }} <span class="font-normal ml-1">Sun</span></p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-[9px] font-black uppercase tracking-widest text-red-500/60">Rest Day</span>
                                                <div class="w-7"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">No DTR logs found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Global Setting Footer --}}
                <div class="relative z-10 shrink-0 border-t border-gray-800 pt-4 mt-auto">
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest select-none">Include OT in Master Total</span>
                        <div class="relative">
                            <input type="checkbox" wire:model.live="includeOvertimeInTotal" class="sr-only peer">
                            <div class="w-7 h-4 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-green-500"></div>
                        </div>
                    </label>
                </div>

            </div>
        </div>
    </div>

    {{-- THE ENTRY MODAL --}}
    @if($showModal)
        <teleport to="body">
            <div class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto" style="z-index: 99999;">
                <div @click.away="$wire.resetForm()" class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full p-6 md:p-8 my-8 relative">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-heading text-2xl font-black text-gray-900 tracking-tight">{{ $editingBlogId ? 'Edit Journal Entry' : 'New Journal Entry' }}</h3>
                        <button wire:click="resetForm" class="text-gray-400 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 p-2 rounded-full transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    <form wire:submit.prevent="saveBlog" class="space-y-5">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Date</label>
                                <input type="date" wire:model="reportDate" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Log Type</label>
                                <select wire:model="type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all">
                                    <option value="daily_report">Daily Log</option>
                                    <option value="weekly_summary">Weekly Summary</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Task Title</label>
                            <input type="text" wire:model="title" placeholder="e.g., Database Migration & UI Updates" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all">
                            @error('title') <span class="text-[10px] text-red-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Activities / Learnings</label>
                            <textarea wire:model="content" rows="4" placeholder="Detail your specific tasks, challenges, and what you learned today..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all resize-none"></textarea>
                            @error('content') <span class="text-[10px] text-red-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Activity Photo --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Attach Photo Documentation (Optional)</label>
                            <input type="file" wire:model="photo" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[10px] file:uppercase file:tracking-widest file:font-black file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-all cursor-pointer">
                            <div wire:loading wire:target="photo" class="text-[10px] text-red-600 font-bold mt-2 animate-pulse">Uploading securely...</div>
                            @error('photo') <span class="text-[10px] text-red-600 font-bold mt-2">{{ $message }}</span> @enderror
                            
                            {{-- Live Image Preview --}}
                            @if ($photo && !$errors->has('photo'))
                                <div class="mt-3 relative rounded-xl overflow-hidden border border-gray-200 inline-block h-32 shadow-sm">
                                    <img src="{{ $photo->temporaryUrl() }}" class="h-full object-cover">
                                    <button type="button" wire:click="$set('photo', null)" class="absolute top-2 right-2 bg-black/50 text-white rounded-full p-1.5 hover:bg-red-600 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @elseif ($existingPhotoPath)
                                <div class="mt-3 relative rounded-xl overflow-hidden border border-gray-200 inline-block h-32 opacity-80 hover:opacity-100 transition shadow-sm">
                                    <img src="{{ asset('storage/' . $existingPhotoPath) }}" class="h-full object-cover">
                                    <span class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[9px] uppercase tracking-widest font-black text-center py-1.5">Current Photo</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                            <button type="button" wire:click="resetForm" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-black uppercase tracking-widest text-[10px] rounded-xl transition-colors">Cancel</button>
                            <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-widest text-[10px] rounded-xl shadow-md transition-all active:scale-95 relative">
                                <span wire:loading.remove wire:target="saveBlog">{{ $editingBlogId ? 'Update Log' : 'Save Entry' }}</span>
                                <span wire:loading wire:target="saveBlog">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </teleport>
    @endif
</div>