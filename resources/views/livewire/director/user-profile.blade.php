<div class="min-h-screen bg-gray-50 pb-20">
    
    {{-- 1. HEADER BANNER --}}
    <div class="relative h-64 md:h-80 bg-gray-800 overflow-hidden">
        {{-- You can make this dynamic later if you allow cover photo uploads --}}
        <div class="absolute inset-0 bg-gradient-to-br from-red-600 via-orange-500 to-green-600 opacity-90"></div>
        
        {{-- OPTIONAL: Add a subtle pattern overlay for texture (CSS only) --}}
        <div class="absolute inset-0 opacity-20" 
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
        
        {{-- Edit Button (Visible only to owner) --}}
        @if(auth()->id() === $user->id)
            <a href="{{ route('profile.edit') }}" class="absolute top-4 right-4 bg-white/10 backdrop-blur-md border border-white/20 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-white/20 transition">
                Edit Profile
            </a>
        @endif
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 -mt-24 md:-mt-32">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            
            {{-- LEFT COLUMN: PROFILE CARD --}}
            <div class="w-full md:w-1/3 lg:w-1/4">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 relative">
                    
                    <div class="p-6 text-center pt-12 relative">
                        {{-- PROFILE PHOTO --}}
                        <div class="absolute -top-16 left-1/2 transform -translate-x-1/2 w-32 h-32">
                            @php
                                $photoPath = $user->profile_photo_path;
                                if (!$photoPath) {
                                    $photoUrl = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF';
                                } elseif (Str::startsWith($photoPath, 'http')) {
                                    $photoUrl = $photoPath;
                                } elseif (Str::startsWith($photoPath, 'images/')) {
                                    // Handle seeder images in public/images
                                    $photoUrl = asset($photoPath);
                                } else {
                                    // Handle uploaded images in storage/app/public
                                    $photoUrl = asset('storage/' . $photoPath);
                                }
                            @endphp
                            
                            <img src="{{ $photoUrl }}" 
                                 class="w-full h-full object-cover rounded-full border-4 border-white shadow-lg bg-white">
                            
                            {{-- BADGE LOGIC --}}
                            @if($user->directorAssignment)
                                <div class="absolute bottom-1 right-1 bg-yellow-400 text-green-900 p-2 rounded-full shadow-md border-2 border-white" title="Director">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                </div>
                            @elseif($user->committeeMember)
                                <div class="absolute bottom-1 right-1 bg-blue-500 text-white p-2 rounded-full shadow-md border-2 border-white" title="Committee Member">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <h1 class="text-xl md:text-2xl font-bold text-gray-900 mt-6">{{ $user->name }}</h1>
                        
                        {{-- DYNAMIC TITLE LOGIC --}}
                        <p class="text-xs md:text-sm font-bold uppercase tracking-wider mb-2 mt-1
                            {{ $user->directorAssignment ? 'text-yellow-600' : 'text-blue-600' }}">
                            @if($user->directorAssignment)
                                {{ $user->directorAssignment->director->name }}
                            @elseif($user->committeeMember)
                                {{ $user->committeeMember->title }}
                                <span class="block text-[10px] text-gray-400 font-normal normal-case">
                                    {{ $user->committeeMember->committee->name ?? 'Committee Member' }}
                                </span>
                            @else
                                Member
                            @endif
                        </p>
                        
                        <div class="text-xs text-gray-500 space-y-1 mb-6">
                            <p>{{ $profile->course ?? 'Course Unspecified' }}</p>
                            <p class="font-medium text-gray-700">{{ $profile->college->name ?? 'Bicol University' }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 border-t border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-3">About</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $profile->bio ?? 'No bio has been added yet.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex-1 space-y-8 mt-16 md:mt-40"> 
    
                {{-- A. PORTFOLIO CARDS --}}
                <section>
                    <h3 class="flex items-center gap-3 text-xl font-bold text-gray-800 mb-5">
                        <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        Portfolio & Experiences
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        @forelse($portfolios as $item)
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    {{ $item->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $item->status }}
                                </span>
                                <span class="text-xs text-gray-400 font-mono">{{ $item->duration }}</span>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg">{{ $item->designation }}</h4>
                            <p class="text-sm text-red-600 font-medium mb-3">{{ $item->place }}</p>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $item->description }}</p>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-8 text-gray-400 bg-white rounded-xl border border-dashed border-gray-300">
                            No portfolios added yet.
                        </div>
                        @endforelse
                    </div>
                </section>

                {{-- B. ENGAGEMENT TABLE --}}
                <section>
                    <h3 class="flex items-center gap-3 text-xl font-bold text-gray-800 mb-5">
                        <span class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                        </span>
                        Engagements
                    </h3>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs">Title / Event</th>
                                    <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs">Details</th>
                                    <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs w-24">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($engagements as $engage)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $engage->title }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ Str::limit($engage->description, 80) }}</td>
                                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $engage->created_at->format('M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                        No engagements recorded yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>
        </div>
    </div>
</div>