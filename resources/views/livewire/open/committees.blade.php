<div class="font-sans antialiased text-gray-800 bg-gray-50 selection:bg-red-500 selection:text-white">
    
    {{-- HERO HEADER --}}
    <header class="relative h-[350px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="Committees Background">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-green-900/80 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Backbone</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-2 drop-shadow-lg">
                Committee Roster
            </h1>
            <p class="text-sm md:text-base text-gray-100 font-light max-w-xl mx-auto italic">
                Driving advocacy through focused action and collaboration.
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">
        
        {{-- Background Blobs --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-0 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-40 right-0 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        </div>

        {{-- ========================================== --}}
        {{-- SECTION 1: ADVOCACY COMMITTEES (Article V) --}}
        {{-- ========================================== --}}
        <section class="relative z-10 mb-32">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-2 h-10 bg-gradient-to-b from-yellow-400 to-red-500 rounded-full"></div>
                <div>
                    <h2 class="font-heading text-3xl font-bold text-gray-900">Advocacy Committees</h2>
                    <p class="text-sm text-gray-500">Tap card to view Committee Head</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($advocacyCommittees as $comm)
                    @include('partials.committee-flip-card', ['committee' => $comm, 'theme' => 'red'])
                @endforeach
            </div>
        </section>

        {{-- =========================================== --}}
        {{-- SECTION 2: STANDING COMMITTEES (Article VI) --}}
        {{-- =========================================== --}}
        <section class="relative z-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-2 h-10 bg-gradient-to-b from-green-500 to-blue-500 rounded-full"></div>
                <div>
                    <h2 class="font-heading text-3xl font-bold text-gray-900">Standing Committees</h2>
                    <p class="text-sm text-gray-500">Operational backbone of the organization (Article VI)</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-5">
                @foreach($standingCommittees as $comm)
                    @include('partials.committee-flip-card', ['committee' => $comm, 'theme' => 'green'])
                @endforeach
            </div>
        </section>

    </div>
    
    {{-- (Footer remains identical) --}}
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">
        </footer>
</div>