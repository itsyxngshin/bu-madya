<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative overflow-x-hidden"
     x-data="{
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        activities: @js($calendarData ?? []),

        // State for sidebar display
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
        hasActivity(day) {
            return this.activities[this.formatDate(day)] !== undefined;
        },
        getDisplayDateFormatted() {
            if(!this.displayDate) return '';
            const parts = this.displayDate.split('-');
            return this.monthNames[parseInt(parts[1]) - 1] + ' ' + parseInt(parts[2]) + ', ' + parts[0];
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },

        // Dynamic Color Mapper for Badges
        getBadgeColor(category) {
            if (category === 'Event' || category === 'Deadline') return 'bg-red-50 text-red-600 border-red-100';
            if (category === 'Active Project' || category === 'Meeting') return 'bg-yellow-50 text-yellow-700 border-yellow-100';
            if (category === 'Completed Project') return 'bg-green-50 text-green-700 border-green-100';
            return 'bg-blue-50 text-blue-600 border-blue-100';
        }
     }">

    {{-- 1. ATMOSPHERE: SIGNATURE BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-stone-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="relative z-10">

        {{-- PAGE HEADER --}}
        <header class="pt-12 md:pt-16 pb-8 md:pb-12 px-5 md:px-6 max-w-7xl mx-auto text-center md:text-left">
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
        </header>

        {{-- MAIN GRID --}}
        <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-8 md:gap-12 items-start">

            {{-- LEFT COLUMN: Activity Sidebar --}}
            <aside class="lg:col-span-5 flex flex-col h-full lg:sticky lg:top-24">

                {{-- Glassmorphism Container --}}
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white overflow-hidden flex flex-col min-h-[600px] relative">

                    {{-- Dynamic Date Header (Elevated Styling) --}}
                    <div class="bg-gradient-to-br from-red-600 to-red-800 text-white p-8 md:p-10 shrink-0 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 via-orange-400 to-red-500"></div>

                        <p class="text-[10px] font-black uppercase tracking-widest text-red-200 mb-2 relative z-10" x-text="displayDate === todayDate ? 'PRESENT DAY' : 'SCHEDULED EVENTS'"></p>
                        <h2 class="font-heading text-4xl md:text-5xl font-black leading-tight drop-shadow-md relative z-10" x-text="getDisplayDateFormatted()"></h2>
                    </div>

                    {{-- Dynamic Activity Feed --}}
                    <div class="p-6 md:p-8 flex-1 overflow-y-auto space-y-5 bg-stone-50/50">

                        {{-- Empty State --}}
                        <div x-show="displayActivities.length === 0" class="flex flex-col items-center justify-center py-10 opacity-70">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-sm font-bold uppercase tracking-wider text-gray-500">No scheduled events.</p>
                        </div>

                        {{-- Dynamic Activity Cards (Mapped from DB) --}}
                        <template x-for="activity in displayActivities" :key="activity.id">
                            <div class="bg-white rounded-[1.5rem] p-6 shadow-sm hover:shadow-xl border border-gray-100 hover:border-red-100 transition-all duration-300 group text-left relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>

                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border"
                                          :class="getBadgeColor(activity.category)"
                                          x-text="activity.category"></span>
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

                        {{-- HARDCODED EXAMPLE (Custom Activity Box) --}}
                        <div class="relative pt-4">
                            <div class="flex items-center gap-3 mb-5 opacity-70">
                                <div class="h-px bg-gray-300 flex-1"></div>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest bg-gray-100 px-2 py-0.5 rounded-full">Custom Activity Preview</span>
                                <div class="h-px bg-gray-300 flex-1"></div>
                            </div>

                            <div class="bg-white rounded-[1.5rem] p-6 shadow-sm hover:shadow-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 group text-left relative overflow-hidden">
                                {{-- Decorative corner shape --}}
                                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>

                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-blue-50 text-blue-600 border border-blue-100">
                                        Executive Meeting
                                    </span>
                                </div>

                                <h3 class="font-bold text-gray-900 text-xl leading-tight mb-3 group-hover:text-blue-600 transition-colors relative z-10">
                                    Project DIGiTS Implementation Sync
                                </h3>

                                <div class="flex items-center gap-2 mb-4 relative z-10">
                                    <div class="w-6 h-6 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">BU MADYA Secretariat</span>
                                </div>

                                <p class="text-sm text-gray-600 font-medium mb-5 line-clamp-3 relative z-10">
                                    Pre-deployment logistics preparation and finalizing partnerships with DEVCON and BICOURSE for the upcoming digital literacy outreach program.
                                </p>

                                <a href="#" class="inline-flex items-center text-[10px] font-black text-gray-900 hover:text-blue-600 uppercase tracking-widest transition-colors group/link relative z-10">
                                    View Link <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </aside>

            {{-- RIGHT COLUMN: Calendar Grid --}}
            <main class="lg:col-span-7">

                {{-- Glassmorphism Container --}}
                <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-white/60 p-6 md:p-10">

                    {{-- Calendar Header Controls --}}
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

                        {{-- Navigation Arrows --}}
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

                    {{-- Days of Week --}}
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;" class="text-center mb-4">
                        <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="day">
                            <div class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest py-2" x-text="day"></div>
                        </template>
                    </div>

                    {{-- Calendar Grid --}}
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px md:gap-8px;">
                        <template x-for="i in blanksArray" :key="'blank-'+i">
                            <div class="aspect-square"></div>
                        </template>

                        <template x-for="day in daysArray" :key="'day-'+day">
                            <div class="relative w-full aspect-square">
                                <button @mouseenter="hoveredDate = formatDate(day)"
                                        @mouseleave="hoveredDate = null"
                                        @click="lockedDate = formatDate(day)"
                                        class="w-full h-full flex flex-col items-center justify-center rounded-2xl transition-all focus:outline-none relative group border-2"
                                        :class="{
                                            'border-transparent bg-gray-50 hover:bg-gray-100 text-gray-500': !hasActivity(day) && formatDate(day) !== todayDate,
                                            'bg-red-50 text-red-700 font-black border-red-100 hover:bg-red-100 hover:scale-105 shadow-sm': hasActivity(day) && formatDate(day) !== todayDate && displayDate !== formatDate(day),
                                            'border-gray-200 bg-white text-gray-900 font-black shadow-sm': formatDate(day) === todayDate && displayDate !== formatDate(day) && !hasActivity(day),
                                            'border-red-600 bg-red-50 text-red-700 font-black shadow-sm hover:scale-105': formatDate(day) === todayDate && displayDate !== formatDate(day) && hasActivity(day),
                                            'bg-red-600 text-white font-black shadow-md border-red-600 scale-105': displayDate === formatDate(day)
                                        }">

                                    <span x-text="day" class="text-sm md:text-lg z-10"></span>

                                    {{-- Dot indicator for activities --}}
                                    <span x-show="hasActivity(day)"
                                          class="absolute bottom-2 w-1.5 h-1.5 md:w-2 md:h-2 rounded-full transition-colors"
                                          :class="displayDate === formatDate(day) ? 'bg-white' : 'bg-red-500'"></span>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Mobile hint --}}
                    <div class="mt-8 text-center lg:hidden">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest bg-gray-100 inline-block px-3 py-1 rounded-full">Tap a date to view activities.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

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

                {{-- Social Media Links --}}
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            <ul class="space-y-3 text-gray-400 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                <li class="pt-2 mt-2 border-t border-gray-800">
                    <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-white hover:translate-x-1 transition inline-block">Privacy Policy</a>
                </li>
            </ul>

            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500 uppercase tracking-widest text-xs">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-inner">
                    <span class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>
