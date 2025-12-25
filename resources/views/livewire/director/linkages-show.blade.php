<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 1. STICKY NAV --}}
    <div class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-6 transition-all duration-300">
        <a href="{{ route('linkages.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to Network</span>
        </a>
        <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
            Partner <span class="text-blue-600">Profile</span>
        </span>
        <div class="flex items-center gap-3">
            <a href="{{ route('linkages.edit', $partnerId) }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit
            </a>
        </div>
        
    </div>

    {{-- 2. HERO COVER --}}
    <div class="relative h-[300px] md:h-[400px] w-full overflow-hidden">
        <img src="{{ $partner['cover_img'] }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-stone-50 via-stone-50/20 to-transparent"></div>
    </div>

    {{-- 3. MAIN CONTENT CONTAINER --}}
    <div class="max-w-7xl mx-auto px-6 -mt-32 relative z-10 pb-24">
        
        <div class="grid lg:grid-cols-12 gap-8">
            
            {{-- LEFT COLUMN: IDENTITY CARD --}}
            <aside class="lg:col-span-4 space-y-8">
                
                {{-- Profile Card --}}
                <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100 text-center relative overflow-hidden">
                    {{-- Status Banner --}}
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-green-400"></div>

                    {{-- Logo --}}
                    <div class="w-32 h-32 mx-auto bg-white rounded-2xl p-2 shadow-lg -mt-16 mb-6 border border-gray-100 relative z-10">
                        <img src="{{ $partner['logo'] }}" class="w-full h-full object-contain rounded-xl">
                    </div>

                    <h1 class="font-heading font-black text-2xl text-gray-900 leading-tight mb-2">
                        {{ $partner['name'] }}
                    </h1>
                    
                    <div class="flex flex-wrap justify-center gap-2 mb-6">
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded-full">
                            {{ $partner['type'] }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-wider rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ $partner['status'] }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-500 space-y-3 border-t border-gray-100 pt-6 text-left">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div>
                                <span class="block text-[10px] font-bold uppercase text-gray-400">Partner Since</span>
                                <span class="font-medium">{{ $partner['since'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="block text-[10px] font-bold uppercase text-gray-400">Website</span>
                                <a href="#" class="font-medium text-blue-600 hover:underline break-all">{{ $partner['contact']['website'] }}</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <span class="block text-[10px] font-bold uppercase text-gray-400">Address</span>
                                <span class="font-medium">{{ $partner['contact']['address'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SDGs Card --}}
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                        Shared Goals (SDGs)
                    </h3>
                    <div class="flex flex-col gap-2">
                        @foreach($partner['sdgs'] as $sdg)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 {{ $sdg['color'] }} rounded-lg text-white font-black text-lg flex items-center justify-center shadow-sm">
                                {{ $sdg['id'] }}
                            </div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">
                                {{ $sdg['label'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </aside>

            {{-- RIGHT COLUMN: CONTENT --}}
            <main class="lg:col-span-8 space-y-12 pt-8 lg:pt-0">
                
                {{-- About Section --}}
                <section>
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-blue-500 w-16 pb-2 mb-6">About</h3>
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <p class="text-gray-600 leading-relaxed font-serif text-lg">
                            {{ $partner['description'] }}
                        </p>
                        <div class="mt-6">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Scope of Partnership</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $partner['scope']) as $tag)
                                <span class="px-3 py-1 bg-gray-50 text-gray-600 text-xs font-bold rounded-lg border border-gray-200">
                                    {{ trim($tag) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Engagement Timeline --}}
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-red-500 w-32 pb-2">Partnership Journey</h3>
                        <span class="text-xs font-bold text-gray-400">{{ count($partner['engagements']) }} Activities</span>
                    </div>

                    <div class="relative border-l-2 border-gray-200 ml-4 space-y-8 pl-8 pb-4">
                        @foreach($partner['engagements'] as $activity)
                        <div class="relative group">
                            {{-- Dot --}}
                            <div class="absolute -left-[41px] top-1 w-6 h-6 bg-white rounded-full border-4 border-gray-200 group-hover:border-red-500 transition-colors duration-300"></div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                <h4 class="font-heading font-bold text-lg text-gray-900 group-hover:text-red-600 transition">
                                    {{ $activity['title'] }}
                                </h4>
                                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">
                                    {{ $activity['date'] }}
                                </span>
                            </div>
                            
                            <span class="inline-block text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2">
                                {{ $activity['type'] }}
                            </span>
                            
                            <p class="text-sm text-gray-600 leading-relaxed bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                {{ $activity['desc'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </section>

                {{-- Joint Projects --}}
                <section>
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-yellow-500 w-24 pb-2 mb-6">Joint Projects</h3>
                    
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach($partner['joint_projects'] as $proj)
                        <a href="#" class="group relative aspect-video rounded-2xl overflow-hidden shadow-md">
                            <img src="{{ $proj['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-80 group-hover:opacity-100 transition"></div>
                            <div class="absolute bottom-4 left-4">
                                <span class="text-[10px] font-bold text-yellow-400 uppercase tracking-widest">Project</span>
                                <h4 class="font-bold text-white text-lg leading-tight">{{ $proj['title'] }}</h4>
                            </div>
                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur rounded-full p-2 opacity-0 group-hover:opacity-100 transition transform translate-y-2 group-hover:translate-y-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </section>

            </main>
        </div>
    </div>
    
    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs text-gray-500">
            &copy; 2025 BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>
