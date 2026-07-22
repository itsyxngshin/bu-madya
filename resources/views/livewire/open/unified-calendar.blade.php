<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative overflow-x-hidden pb-20"
     x-data="{
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        activities: @js($calendarData ?? []),

        todayDate: new Date().getFullYear() + '-' + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + String(new Date().getDate()).padStart(2, '0'),
        hoveredDate: null,
        lockedDate: null,

        get displayDate() {
            return this.hoveredDate || this.lockedDate || this.todayDate;
        },

        get displayActivities() {
            return this.activities[this.displayDate] || [];
        },

        get daysInMonth() { return new Date(this.currentYear, this.currentMonth + 1, 0).getDate(); },
        get blankDays() { return new Date(this.currentYear, this.currentMonth, 1).getDay(); },
        get daysArray() { return Array.from({length: this.daysInMonth}, (_, i) => i + 1); },
        get blanksArray() { return Array.from({length: this.blankDays}, (_, i) => i); },

        formatDate(day) {
            return this.currentYear + '-' + String(this.currentMonth + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
        },

        hasActivity(day) { return this.activities[this.formatDate(day)] !== undefined; },

        // Multi-day Visual Merging Logic
        isStart(day) {
            let acts = this.activities[this.formatDate(day)];
            return acts ? acts.some(a => a.is_multi && a.is_start) : false;
        },
        isEnd(day) {
            let acts = this.activities[this.formatDate(day)];
            return acts ? acts.some(a => a.is_multi && a.is_end) : false;
        },
        isMiddle(day) {
            let acts = this.activities[this.formatDate(day)];
            return acts ? acts.some(a => a.is_multi && !a.is_start && !a.is_end) : false;
        },

        getDisplayDateFormatted() {
            if(!this.displayDate) return '';
            const parts = this.displayDate.split('-');
            return this.monthNames[parseInt(parts[1]) - 1] + ' ' + parseInt(parts[2]) + ', ' + parts[0];
        },
        prevMonth() {
            if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; }
            else { this.currentMonth--; }
        },
        nextMonth() {
            if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; }
            else { this.currentMonth++; }
        },
        getBadgeColor(category) {
            if (category === 'Event' || category === 'Deadline') return 'bg-red-50 text-red-600 border-red-100';
            if (category === 'Active Project' || category === 'Meeting') return 'bg-yellow-50 text-yellow-700 border-yellow-100';
            if (category === 'Completed Project') return 'bg-green-50 text-green-700 border-green-100';
            return 'bg-blue-50 text-blue-600 border-blue-100';
        }
     }">

    {{-- ATMOSPHERE --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-stone-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="relative z-10">

        {{-- PAGE HEADER --}}
        <header class="pt-24 md:pt-32 pb-8 md:pb-12 px-5 md:px-6 max-w-7xl mx-auto text-center md:text-left flex flex-col md:flex-row md:justify-between md:items-end gap-6">
            <div>
                <div class="mb-4 relative z-20">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition">
                        &larr; Back to Home
                    </a>
                </div>

                <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md border border-white/50 px-4 py-1.5 rounded-full mb-4 shadow-sm">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Organizational Timeline</span>
                </div>

                <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-[1.1] drop-shadow-sm tracking-tighter">
                    Unified <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Calendar</span>
                </h1>
                <p class="text-gray-500 font-medium mt-3 uppercase tracking-widest text-xs md:text-sm">Discover events, deadlines, and milestones.</p>
            </div>

            {{-- ADMIN CREATE BUTTON --}}
            @if(auth('ibalong')->check() && in_array(auth('ibalong')->user()->role_id, [1, 2]))
                <button wire:click="openModal" class="inline-flex items-center justify-center gap-2 bg-gray-900 text-white font-bold px-6 py-3 rounded-xl shadow-lg hover:bg-red-600 hover:-translate-y-1 transition-all text-xs uppercase tracking-widest z-20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Add Activity
                </button>
            @endif
        </header>

        {{-- MAIN GRID --}}
        <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-8 md:gap-12 items-start">

            {{-- LEFT COLUMN: Activity Sidebar --}}
            <aside class="lg:col-span-5 flex flex-col h-full lg:sticky lg:top-24">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white overflow-hidden flex flex-col min-h-[600px] relative">
                    <div class="bg-gradient-to-br from-red-600 to-red-800 text-white p-8 md:p-10 shrink-0 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 via-orange-400 to-red-500"></div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-red-200 mb-2 relative z-10" x-text="displayDate === todayDate ? 'PRESENT DAY' : 'SCHEDULED EVENTS'"></p>
                        <h2 class="font-heading text-4xl md:text-5xl font-black leading-tight drop-shadow-md relative z-10" x-text="getDisplayDateFormatted()"></h2>
                    </div>

                    <div class="p-6 md:p-8 flex-1 overflow-y-auto space-y-5 bg-stone-50/50">
                        <div x-show="displayActivities.length === 0" class="flex flex-col items-center justify-center py-10 opacity-70">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-sm font-bold uppercase tracking-wider text-gray-500">No scheduled events.</p>
                        </div>

                        <template x-for="activity in displayActivities" :key="activity.id">
                            <div class="bg-white rounded-[1.5rem] p-6 shadow-sm hover:shadow-xl border border-gray-100 hover:border-red-100 transition-all duration-300 group text-left relative overflow-hidden">
                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border" :class="getBadgeColor(activity.category)" x-text="activity.category"></span>
                                </div>
                                <h3 class="font-bold text-gray-900 text-xl leading-tight mb-3 group-hover:text-red-600 transition-colors relative z-10" x-text="activity.title"></h3>
                                <div x-show="activity.organizer" class="flex items-center gap-2 mb-4 relative z-10">
                                    <div class="w-6 h-6 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide" x-text="activity.organizer"></span>
                                </div>
                                <p x-show="activity.description" class="text-sm text-gray-600 font-medium mb-5 line-clamp-3 relative z-10" x-text="activity.description"></p>
                                <a x-show="activity.link" :href="activity.link" target="_blank" class="inline-flex items-center text-[10px] font-black text-gray-900 hover:text-red-600 uppercase tracking-widest transition-colors mt-2 group/link relative z-10">
                                    Access Portal <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </aside>

            {{-- RIGHT COLUMN: Calendar Grid --}}
            <main class="lg:col-span-7">
                <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-white/60 p-6 md:p-10">

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-100 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-heading font-black text-gray-900 text-2xl md:text-3xl tracking-tight leading-none mb-1" x-text="monthNames[currentMonth]"></h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none" x-text="currentYear"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 bg-gray-50 rounded-xl border border-gray-200 p-1 shadow-inner">
                            <button @click="prevMonth()" class="p-2 md:p-3 text-gray-500 hover:text-red-600 hover:bg-white rounded-lg transition-all focus:outline-none hover:shadow-sm active:scale-95">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <div class="w-[1px] h-6 bg-gray-200"></div>
                            <button @click="nextMonth()" class="p-2 md:p-3 text-gray-500 hover:text-red-600 hover:bg-white rounded-lg transition-all focus:outline-none hover:shadow-sm active:scale-95">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;" class="text-center mb-4">
                        <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="day">
                            <div class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest py-2" x-text="day"></div>
                        </template>
                    </div>

                    {{-- Calendar Grid (WITH MULTI-DAY MERGE LOGIC) --}}
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px md:gap-8px; row-gap: 8px;">
                        <template x-for="i in blanksArray" :key="'blank-'+i">
                            <div class="aspect-square"></div>
                        </template>

                        <template x-for="day in daysArray" :key="'day-'+day">
                            <div class="relative w-full aspect-square px-1">
                                <button @mouseenter="hoveredDate = formatDate(day)"
                                        @mouseleave="hoveredDate = null"
                                        @click="lockedDate = formatDate(day)"
                                        class="w-full h-full flex flex-col items-center justify-center transition-all focus:outline-none relative group border-y-2"
                                        :class="{
                                            /* Radius logic for Merging Multi-days */
                                            'rounded-2xl border-x-2': !isStart(day) && !isMiddle(day) && !isEnd(day),
                                            'rounded-l-2xl rounded-r-none border-l-2 border-r-0 -mr-2 pr-2 z-10': isStart(day) && !isEnd(day),
                                            'rounded-none border-x-0 -mx-2 px-4 z-0': isMiddle(day),
                                            'rounded-r-2xl rounded-l-none border-r-2 border-l-0 -ml-2 pl-2 z-10': isEnd(day) && !isStart(day),

                                            /* Color Logic */
                                            'border-transparent bg-gray-50 hover:bg-gray-100 text-gray-500': !hasActivity(day) && formatDate(day) !== todayDate,
                                            'bg-red-50 text-red-700 font-black border-red-100 hover:bg-red-100': hasActivity(day) && formatDate(day) !== todayDate && displayDate !== formatDate(day),
                                            'border-gray-200 bg-white text-gray-900 font-black shadow-sm': formatDate(day) === todayDate && displayDate !== formatDate(day) && !hasActivity(day),
                                            'border-red-600 bg-red-50 text-red-700 font-black shadow-sm': formatDate(day) === todayDate && displayDate !== formatDate(day) && hasActivity(day),
                                            'bg-red-600 text-white font-black shadow-md border-red-600': displayDate === formatDate(day)
                                        }">

                                    <span x-text="day" class="text-sm md:text-lg z-20"></span>

                                    {{-- Dot indicator for single activities --}}
                                    <span x-show="hasActivity(day) && !isStart(day) && !isMiddle(day) && !isEnd(day)"
                                          class="absolute bottom-1.5 w-1.5 h-1.5 md:w-2 md:h-2 rounded-full transition-colors z-20"
                                          :class="displayDate === formatDate(day) ? 'bg-white' : 'bg-red-500'"></span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- ADMIN: CREATE ACTIVITY MODAL --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('isModalOpen', false)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden text-left">

                    <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-heading font-black text-gray-900">Add Calendar Activity</h3>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Direct scheduling insertion</p>
                        </div>
                        <button wire:click="$set('isModalOpen', false)" class="text-gray-400 hover:text-red-500 bg-white p-2 rounded-full shadow-sm"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <form wire:submit.prevent="saveActivity">
                        <div class="p-8 space-y-5">

                            @if (session()->has('success'))
                                <div class="p-4 bg-green-50 border border-green-100 rounded-xl text-green-700 text-sm font-bold">{{ session('success') }}</div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Activity Title *</label>
                                    <input type="text" wire:model="title" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent font-bold">
                                    @error('title') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Start Date *</label>
                                    <input type="date" wire:model.live="start_date" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent font-bold">
                                    @error('start_date') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">End Date (Optional)</label>
                                    <input type="date" wire:model="end_date" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent font-bold">
                                    @error('end_date') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Category *</label>
                                    <select wire:model="category" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent font-bold">
                                        <option value="Activity">General Activity</option>
                                        <option value="Meeting">Meeting</option>
                                        <option value="Deadline">Deadline</option>
                                        <option value="Event">Event</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Organizer (Optional)</label>
                                    <input type="text" wire:model="organizer" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent font-bold">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">External Link (Optional)</label>
                                    <input type="url" wire:model="external_link" placeholder="https://" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent font-bold">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Description / Notes</label>
                                    <textarea wire:model="description" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent font-bold"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end gap-3 rounded-b-[2rem]">
                            <button type="button" wire:click="$set('isModalOpen', false)" class="px-6 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-200 transition-colors">Cancel</button>
                            <button type="submit" class="bg-gray-900 hover:bg-red-600 text-white font-bold px-8 py-2.5 rounded-xl shadow-lg hover:shadow-red-500/30 transition-all text-xs uppercase tracking-widest">Publish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-bold text-2xl tracking-tight">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-6 text-sm">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                </div>
            </div>
            <ul class="space-y-3 text-gray-400 text-sm">
                <li><a href="#" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
            </ul>
            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500 uppercase tracking-widest text-xs">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-inner">
                    <span class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">{{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">&copy; {{ date('Y') }} BU MADYA. All Rights Reserved.</div>
    </footer>
</div>
