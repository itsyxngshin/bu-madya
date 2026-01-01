<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 1. HERO SECTION (Updated Gradient) --}}
    <div class="relative bg-gray-900 h-[500px] flex items-center justify-center overflow-hidden">
        {{-- Background Image with Tri-Color Gradient Overlay --}}
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30">
            {{-- Gradient: Red -> Yellow -> Green --}}
            <div class="absolute inset-0 bg-gradient-to-br from-red-900/90 via-yellow-900/60 to-green-900/80"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto space-y-6">
            <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-green-300 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                Est. Bicol University
            </span>
            <h1 class="font-heading font-black text-5xl md:text-7xl text-white tracking-tight">
                BU <span class="text-yellow-400">MADYA</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 font-serif italic max-w-2xl mx-auto">
                "Iriba sa magkasarong lakdang. Tara, madya na!"
            </p>
            <div class="flex items-center justify-center gap-2 text-sm font-bold uppercase tracking-widest">
                <span class="text-red-400">Passion</span>
                <span class="text-gray-600">•</span>
                <span class="text-yellow-400">Empowerment</span>
                <span class="text-gray-600">•</span>
                <span class="text-green-400">Sustainability</span>
            </div>
        </div>
    </div>

    {{-- 2. WHO WE ARE (Updated Colors) --}}
    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">
                    Who We <span class="text-red-600">Are</span>
                </h2>
                <div class="prose text-gray-600 leading-relaxed text-lg">
                    <p>
                        The <strong class="text-gray-900">Movement for the Advancement of Youth-led Advocacy (BU MADYA)</strong> is a non-political student organization anchored on the passion for community advocacy.
                    </p>
                    <p>
                        We affirm our commitment to making education accessible, championing the open pursuit of truth, and fostering a society grounded in <span class="text-green-600 font-bold">sustainable development</span> and <span class="text-red-600 font-bold">social justice</span>.
                    </p>
                </div>
                
                {{-- Tri-Color Badges --}}
                <div class="flex flex-wrap gap-3 pt-4">
                    <span class="px-4 py-2 bg-red-50 text-red-700 font-bold rounded-lg text-sm border border-red-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-600"></span> Inclusivity
                    </span>
                    <span class="px-4 py-2 bg-yellow-50 text-yellow-700 font-bold rounded-lg text-sm border border-yellow-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Empowerment
                    </span>
                    <span class="px-4 py-2 bg-green-50 text-green-700 font-bold rounded-lg text-sm border border-green-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-600"></span> Sustainability
                    </span>
                </div>
            </div>
            
            <div class="relative">
                <div class="absolute -inset-4 bg-gradient-to-br from-red-100 to-green-100 rounded-3xl transform -rotate-2"></div>
                <div class="relative bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-4 uppercase text-sm tracking-widest">Our Purpose</h3>
                    <p class="text-gray-600 italic border-l-4 border-green-500 pl-4 mb-6">
                        "To empower and mobilize the youth and community stakeholders to address pertinent issues through collaboration, education, and dialogue."
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <span>Grassroots Initiatives</span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span>Multi-level Participation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. [NEW] FLAGSHIP PROJECTS PLACEHOLDERS --}}
    <div class="bg-white py-24 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div class="max-w-2xl">
                    <span class="text-green-600 font-bold tracking-widest uppercase text-xs mb-2 block">Our Impact</span>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">
                        Flagship <span class="text-yellow-500">Initiatives</span>
                    </h2>
                    <p class="text-gray-500 mt-4 text-lg">
                        Driving change through strategic projects anchored on our core advocacy pillars.
                    </p>
                </div>
                <a href="{{ route('projects.index') }}" class="group flex items-center gap-2 text-sm font-bold text-red-600 hover:text-red-700 transition">
                    View All Projects
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Placeholder Project 1 (Green Focus) --}}
                <div class="group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb7d5763?q=80&w=2000&auto=format&fit=crop" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute bottom-0 left-0 p-8 z-20">
                        <span class="inline-block px-3 py-1 bg-green-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full mb-3">Environment</span>
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-green-300 transition-colors">Project ESCAPE</h3>
                        <p class="text-gray-300 text-sm line-clamp-2">
                            Empowering Safe Cyberspace and Protection from Exploitation through digital literacy.
                        </p>
                    </div>
                </div>

                {{-- Placeholder Project 2 (Red Focus) --}}
                <div class="group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=2070&auto=format&fit=crop" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute bottom-0 left-0 p-8 z-20">
                        <span class="inline-block px-3 py-1 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full mb-3">Education</span>
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-red-300 transition-colors">Bicol Youth Summit</h3>
                        <p class="text-gray-300 text-sm line-clamp-2">
                            A regional gathering of student leaders to discuss policy making and social innovation.
                        </p>
                    </div>
                </div>

                {{-- Placeholder Project 3 (Yellow Focus) --}}
                <div class="group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?q=80&w=2074&auto=format&fit=crop" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute bottom-0 left-0 p-8 z-20">
                        <span class="inline-block px-3 py-1 bg-yellow-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full mb-3">Community</span>
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-yellow-300 transition-colors">Grassroots Outreach</h3>
                        <p class="text-gray-300 text-sm line-clamp-2">
                            Direct community engagement programs providing essential aid and skills training.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. OBJECTIVES (Updated with Color Accents) --}}
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-4">
                    Our <span class="text-red-600">Objectives</span>
                </h2>
                <p class="text-gray-500">
                    Aligning initiatives with <span class="font-bold text-green-600">United Nation Sustainable Development Goals</span> and national priorities.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Updated calls to component with specific colors --}}
                <x-objective-card icon="globe" title="Sustainable Development" color="green"
                    desc="Aligning initiatives with UN SDGs and supporting national development priorities." />
                
                <x-objective-card icon="academic-cap" title="Inclusive Education" color="red"
                    desc="Providing platforms for learning, civic engagement, and intercultural understanding." />

                <x-objective-card icon="users" title="Active Citizenship" color="yellow"
                    desc="Promoting human rights, social justice, and democratic values through participatory programs." />

                <x-objective-card icon="lightning-bolt" title="Youth Capacity" color="yellow"
                    desc="Engaging youth in leadership development, advocacy campaigns, and skills training." />

                <x-objective-card icon="heart" title="Peace & Culture" color="red"
                    desc="Fostering intercultural dialogue and respecting diversity across communities." />

                <x-objective-card icon="hand" title="Community Partnership" color="green"
                    desc="Addressing local issues: Environment, Climate Action, Health, and Mental Well-being." />
            </div>
        </div>
    </div>

    <div class="bg-white py-24 border-t border-gray-200" x-data="{ 
        activeSdg: null,
        sdgs: [
            { id: 1, title: 'No Poverty', color: '#E5243B', desc: 'End poverty in all its forms everywhere.' },
            { id: 2, title: 'Zero Hunger', color: '#DDA63A', desc: 'End hunger, achieve food security and improved nutrition and promote sustainable agriculture.' },
            { id: 3, title: 'Good Health and Well-being', color: '#4C9F38', desc: 'Ensure healthy lives and promote well-being for all at all ages.' },
            { id: 4, title: 'Quality Education', color: '#C5192D', desc: 'Ensure inclusive and equitable quality education and promote lifelong learning opportunities for all.' },
            { id: 5, title: 'Gender Equality', color: '#FF3A21', desc: 'Achieve gender equality and empower all women and girls.' },
            { id: 6, title: 'Clean Water and Sanitation', color: '#26BDE2', desc: 'Ensure availability and sustainable management of water and sanitation for all.' },
            { id: 7, title: 'Affordable and Clean Energy', color: '#FCC30B', desc: 'Ensure access to affordable, reliable, sustainable and modern energy for all.' },
            { id: 8, title: 'Decent Work and Economic Growth', color: '#A21942', desc: 'Promote sustained, inclusive and sustainable economic growth, full and productive employment and decent work for all.' },
            { id: 9, title: 'Industry, Innovation and Infrastructure', color: '#FD6925', desc: 'Build resilient infrastructure, promote inclusive and sustainable industrialization and foster innovation.' },
            { id: 10, title: 'Reduced Inequalities', color: '#DD1367', desc: 'Reduce inequality within and among countries.' },
            { id: 11, title: 'Sustainable Cities and Communities', color: '#FD9D24', desc: 'Make cities and human settlements inclusive, safe, resilient and sustainable.' },
            { id: 12, title: 'Responsible Consumption and Production', color: '#BF8B2E', desc: 'Ensure sustainable consumption and production patterns.' },
            { id: 13, title: 'Climate Action', color: '#3F7E44', desc: 'Take urgent action to combat climate change and its impacts.' },
            { id: 14, title: 'Life Below Water', color: '#0A97D9', desc: 'Conserve and sustainably use the oceans, seas and marine resources for sustainable development.' },
            { id: 15, title: 'Life on Land', color: '#56C02B', desc: 'Protect, restore and promote sustainable use of terrestrial ecosystems, sustainably manage forests, combat desertification, and halt and reverse land degradation and halt biodiversity loss.' },
            { id: 16, title: 'Peace, Justice and Strong Institutions', color: '#00689D', desc: 'Promote peaceful and inclusive societies for sustainable development, provide access to justice for all and build effective, accountable and inclusive institutions at all levels.' },
            { id: 17, title: 'Partnerships for the Goals', color: '#19486A', desc: 'Strengthen the means of implementation and revitalize the global partnership for sustainable development.' }
        ]
    }">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-green-600 font-bold tracking-widest uppercase text-xs mb-2 block">Global Framework</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">
                    The 17 Sustainable <span class="text-blue-600">Development Goals</span>
                </h2>
                <p class="text-gray-500 mt-4 text-lg">
                    BU MADYA aligns its advocacies with the United Nations' blueprint for peace and prosperity. Click on a goal to learn more.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                
                <template x-for="sdg in sdgs" :key="sdg.id">
                    <div class="relative group">
                        {{-- The Card Button --}}
                        <button @click="activeSdg === sdg.id ? activeSdg = null : activeSdg = sdg.id"
                                class="w-full text-left h-full min-h-[140px] rounded-xl p-4 flex flex-col justify-between transition-all duration-300 transform hover:scale-105 hover:shadow-xl hover:z-10"
                                :class="activeSdg === sdg.id ? 'scale-105 shadow-xl z-20 ring-4 ring-offset-2 ring-gray-200' : 'shadow-md'"
                                :style="`background-color: ${sdg.color};`">
                            
                            <div class="flex justify-between items-start">
                                <span class="text-white/80 font-black text-2xl" x-text="sdg.id"></span>
                                {{-- Chevron Icon that rotates --}}
                                <svg class="w-5 h-5 text-white transition-transform duration-300" 
                                    :class="activeSdg === sdg.id ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            
                            <h3 class="text-white font-bold text-xs md:text-sm uppercase leading-tight mt-2" x-text="sdg.title"></h3>
                        </button>

                        {{-- Collapsible Description --}}
                        {{-- We use a dedicated absolute panel for smoother UX in a tight grid, OR standard collapse --}}
                        <div x-show="activeSdg === sdg.id" 
                            x-collapse 
                            x-cloak
                            class="absolute top-full left-0 right-0 z-30 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 p-4 w-[150%] md:w-[120%] lg:w-full origin-top-left"
                            :style="`border-top: 4px solid ${sdg.color};`">
                            
                            <p class="text-gray-600 text-xs md:text-sm leading-relaxed" x-text="sdg.desc"></p>
                            
                            {{-- Close Button (Optional) --}}
                            <button @click.stop="activeSdg = null" class="text-[10px] font-bold text-gray-400 mt-2 hover:text-gray-900 uppercase">
                                Close
                            </button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    {{-- 5. MEMBERSHIP SECTION (Updated with Green Action) --}}
    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="bg-gray-900 rounded-[3rem] overflow-hidden shadow-2xl relative">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-800 to-red-900 opacity-90"></div>
            
            <div class="grid md:grid-cols-2 gap-12 p-12 md:p-16 items-center relative z-10">
                <div class="text-white space-y-6">
                    <h2 class="text-3xl font-black uppercase tracking-tight">Become a Member Advocate</h2>
                    <p class="text-gray-300 text-lg">
                        Join a community dedicated to making a difference. Membership is open to all bona fide college students of Bicol University.
                    </p>
                    
                    <div class="space-y-3 mt-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="font-bold text-sm">Non-Political & Inclusive</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="font-bold text-sm">Valid for 1 Academic Year</span>
                        </div>
                    </div>

                    <div class="pt-6">
                        {{-- Green Button for "Go/Action" --}}
                        <a href="{{ route('membership-form') }}" class="inline-block bg-green-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-green-500 transition shadow-lg shadow-green-900/50 transform hover:-translate-y-1">
                            Register Now
                        </a>
                    </div>
                </div>

                {{-- Visual --}}
                <div class="hidden md:flex justify-center relative">
                     <div class="absolute inset-0 bg-green-500/20 blur-3xl rounded-full"></div>
                     <img src="{{ asset('images/official_logo.png') }}" class="relative w-55 h-55 object-contain drop-shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</div>