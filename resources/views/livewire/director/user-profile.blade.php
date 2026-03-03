<div class="min-h-screen bg-gray-50 pb-20 relative isolate overflow-hidden">

    {{-- 0. BACKGROUND DECORATION BLOBS --}}
    <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-blue-200 mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute top-1/3 -right-20 w-80 h-80 rounded-full bg-orange-200 mix-blend-multiply filter blur-3xl opacity-30"></div>
        <div class="absolute -bottom-20 left-20 w-80 h-80 rounded-full bg-teal-200 mix-blend-multiply filter blur-3xl opacity-30"></div>
    </div>

    {{-- 1. HEADER BANNER (Dynamic Colors Based on Role) --}}
    <div class="relative h-64 md:h-80 bg-gray-800 overflow-hidden shadow-md">
        @if($user?->role?->role_name === 'organization')
            {{-- Organization Gradient (Blue/Indigo/Purple) --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-indigo-600 to-purple-800 opacity-90"></div>
        @else
            {{-- Standard User Gradient (Red/Orange/Green) --}}
            <div class="absolute inset-0 bg-gradient-to-br from-red-600 via-orange-500 to-green-600 opacity-90"></div>
        @endif

        <div class="absolute inset-0 opacity-20"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>

        @if(auth()->check() && auth()->id() === $user?->id)
            <a href="{{ route('profile.edit') }}" class="absolute top-4 right-4 bg-white/10 backdrop-blur-md border border-white/20 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-white/20 transition">
                Edit Profile
            </a>
        @endif
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 -mt-24 md:-mt-32">
        <div class="flex flex-col md:flex-row gap-8 items-start">

            {{-- ================================================================= --}}
            {{-- ORGANIZATION PROFILE LAYOUT                                       --}}
            {{-- ================================================================= --}}
            @if($user?->role?->role_name === 'organization')

                {{-- LEFT COLUMN: ORG CARD --}}
                <div class="w-full md:w-1/3 lg:w-1/4 mt-16 md:mt-0">
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/50 relative">

                        {{-- [FIXED] Changed pt-12 to pt-24 so the text clears the logo completely --}}
                        <div class="p-6 text-center pt-24 relative">
                            {{-- LOGO --}}
                            <div class="absolute -top-16 left-1/2 transform -translate-x-1/2 w-32 h-32">
                                @php
                                    $photoPath = $user?->profile_photo_path;
                                    $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($user?->name ?? 'Org').'&color=4F46E5&background=E0E7FF';
                                @endphp
                                <img src="{{ $photoUrl }}" class="w-full h-full object-cover rounded-2xl border-4 border-white shadow-lg bg-white">
                            </div>

                            <h1 class="text-xl md:text-2xl font-black text-gray-900 leading-tight">{{ $user?->name ?? 'Unknown Organization' }}</h1>
                            <p class="text-[10px] font-black uppercase tracking-widest mb-4 mt-2 text-blue-600 bg-blue-50 py-1.5 px-3 rounded-full inline-block border border-blue-100">
                                Organization
                            </p>

                            {{-- College Fallback Check --}}
                            @if($profile?->college)
                                <div class="mt-4 mb-2 flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-100 transition text-left">
                                    <div class="w-8 h-8 rounded-lg bg-white text-indigo-500 shadow-sm flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Based In</p>
                                        <p class="text-xs font-bold text-gray-700 leading-tight">{{ $profile->college->name }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50/80 p-6 border-t border-gray-100 rounded-b-3xl">
                            <h3 class="text-xs font-bold text-gray-400 uppercase mb-3">About the Organization</h3>
                            <p class="text-sm text-gray-600 leading-relaxed font-medium">
                                {{ $profile?->bio ?? 'No organization details provided yet.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: ORG ENGAGEMENTS --}}
                <div class="flex-1 space-y-8 mt-12 md:mt-40 min-w-0">
                    <section>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="flex items-center gap-3 text-xl font-bold text-gray-800 relative z-10">
                                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </span>
                                Partnership & Engagements
                            </h3>
                            @if(isset($engagements) && $engagements->count() > 0)
                                <span class="text-xs font-bold text-gray-400 bg-white px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm">
                                    {{ $engagements->count() }} Activities
                                </span>
                            @endif
                        </div>

                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50/50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs">Event / Campaign</th>
                                        <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs hidden sm:table-cell">Description</th>
                                        <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs w-32">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($engagements ?? [] as $engage)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900">{{ $engage->title }}</td>
                                        <td class="px-6 py-4 text-gray-600 leading-relaxed hidden sm:table-cell">{{ Str::limit($engage->description, 100) }}</td>
                                        <td class="px-6 py-4 text-gray-400 text-xs font-bold">{{ $engage->created_at->format('M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No activities recorded yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>


            {{-- ================================================================= --}}
            {{-- STANDARD USER PROFILE LAYOUT                                      --}}
            {{-- ================================================================= --}}
            @else

                {{-- LEFT COLUMN: PROFILE CARD --}}
                <div class="w-full md:w-1/3 lg:w-1/4 mt-16 md:mt-0">
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/50 relative">

                        {{-- [FIXED] Changed pt-12 to pt-24 so the text clears the logo completely --}}
                        <div class="p-6 text-center pt-24 relative">
                            {{-- PROFILE PHOTO --}}
                            <div class="absolute -top-16 left-1/2 transform -translate-x-1/2 w-32 h-32">
                                @php
                                    $photoPath = $user?->profile_photo_path;
                                    $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($user?->name ?? 'User').'&color=7F9CF5&background=EBF4FF';
                                @endphp

                                <img src="{{ $photoUrl }}" class="w-full h-full object-cover rounded-full border-4 border-white shadow-lg bg-white">

                                {{-- BADGE LOGIC [FIXED BORDER AND SPACING] --}}
                                @if($user?->directorAssignment)
                                    <div class="absolute -bottom-1 -right-1 bg-yellow-400 text-yellow-900 p-2 rounded-full shadow-md border-4 border-white flex items-center justify-center" title="Director">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    </div>
                                @elseif($user?->committeeMember)
                                    <div class="absolute -bottom-1 -right-1 bg-blue-500 text-white p-2 rounded-full shadow-md border-4 border-white flex items-center justify-center" title="Committee Member">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            <h1 class="text-xl md:text-2xl font-black text-gray-900 leading-tight">{{ $user?->name ?? 'Unknown User' }}</h1>

                            <p class="text-[10px] font-black uppercase tracking-widest mb-4 mt-2 {{ $user?->directorAssignment ? 'text-yellow-600 bg-yellow-50 border-yellow-100' : 'text-blue-600 bg-blue-50 border-blue-100' }} py-1 px-3 rounded-full inline-block border">
                                @if($user?->directorAssignment)
                                    {{ $user->directorAssignment->director->name }}
                                @elseif($user?->committeeMember)
                                    {{ $user->committeeMember->title }}
                                    <span class="block text-[8px] text-gray-400 font-bold normal-case mt-0.5 tracking-normal">
                                        {{ $user->committeeMember->committee?->name ?? 'Committee Member' }}
                                    </span>
                                @else
                                    Member
                                @endif
                            </p>

                            {{-- STYLIZED COLLEGE AND COURSE --}}
                            <div class="mt-6 mb-6 flex flex-col gap-3 text-left">
                                <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-100 transition group">
                                    <div class="w-8 h-8 rounded-lg bg-white text-blue-500 shadow-sm flex items-center justify-center shrink-0 group-hover:scale-110 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Course</p>
                                        <p class="text-sm font-bold text-gray-800 leading-tight">{{ $profile?->course ?? 'Not Specified' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-orange-100 transition group">
                                    <div class="w-8 h-8 rounded-lg bg-white text-orange-500 shadow-sm flex items-center justify-center shrink-0 group-hover:scale-110 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">College</p>
                                        <p class="text-xs font-medium text-gray-600 leading-tight">{{ $profile?->college?->name ?? 'Bicol University' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/80 p-6 border-t border-gray-100 rounded-b-3xl">
                            <h3 class="text-xs font-bold text-gray-400 uppercase mb-3">About</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ $profile?->bio ?? 'No bio has been added yet.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: PORTFOLIO & ENGAGEMENTS --}}
                <div class="flex-1 space-y-8 mt-12 md:mt-40 min-w-0">

                    {{-- A. PORTFOLIO CARDS (COLLAPSIBLE) --}}
                    <section x-data="{ showAll: false }">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="flex items-center gap-3 text-xl font-bold text-gray-800 relative z-10">
                                <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                Portfolio & Experiences
                            </h3>
                            @if(isset($portfolios) && $portfolios->count() > 0)
                                <span class="text-xs font-bold text-gray-400 bg-white px-2 py-1 rounded-md border border-gray-100">
                                    {{ $portfolios->count() }} Total
                                </span>
                            @endif
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            @forelse($portfolios ?? [] as $item)
                                <div
                                    class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300"
                                    @if($loop->index >= 4)
                                        x-show="showAll"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                    @endif
                                >
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="px-2 py-1 rounded text-[10px] uppercase tracking-wider font-black {{ $item->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->status }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $item->duration }}</span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-lg leading-tight mt-1">{{ $item->designation }}</h4>
                                    <p class="text-sm text-red-600 font-medium mb-3">{{ $item->place }}</p>
                                    <p class="text-sm text-gray-500 leading-relaxed">{{ $item->description }}</p>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-10 bg-white/50 rounded-2xl border border-dashed border-gray-300">
                                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No portfolios added yet.</p>
                                </div>
                            @endforelse
                        </div>

                        @if(isset($portfolios) && $portfolios->count() > 4)
                            <div class="mt-6 text-center relative z-20">
                                <button @click="showAll = !showAll" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-white border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-900 shadow-sm transition group">
                                    <span x-text="showAll ? 'Show Less' : 'See All Experiences'"></span>
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="showAll ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </section>

                    {{-- B. ENGAGEMENT TABLE --}}
                    <section>
                        <h3 class="flex items-center gap-3 text-xl font-bold text-gray-800 mb-5 relative z-10">
                            <span class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                            </span>
                            Engagements
                        </h3>

                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50/50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs">Title / Event</th>
                                        <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs hidden sm:table-cell">Details</th>
                                        <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs w-24">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($engagements ?? [] as $engage)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900">{{ $engage->title }}</td>
                                        <td class="px-6 py-4 text-gray-600 hidden sm:table-cell">{{ Str::limit($engage->description, 80) }}</td>
                                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $engage->created_at->format('M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No engagements recorded yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            @endif

        </div>
    </div>
</div>
