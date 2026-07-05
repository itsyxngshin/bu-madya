<div>
    {{-- HERO SECTION --}}
    <header id="home" class="relative min-h-screen flex flex-col items-center justify-center pt-20 px-4 text-center overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#FCD34D 2px, transparent 2px); background-size: 60px 60px;"></div>

        <div class="relative z-10 space-y-8 max-w-4xl mx-auto">
            <h1 class="font-pixel text-4xl md:text-6xl text-[#E58A1F] drop-shadow-[4px_4px_0_#000]">
                FROM EPIC TO IMPACT
            </h1>
            <h2 class="font-pixel text-2xl md:text-4xl text-white drop-shadow-[3px_3px_0_#000]">
                AUGUST 12-13, 2026
            </h2>
            
            <div class="pt-8 relative inline-block">
                <a href="#register" class="btn-pixel inline-block font-pixel text-black bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-500 px-8 py-4 text-xl border-4 border-white cursor-pointer relative z-10">
                    REGISTER
                </a>
                <svg class="absolute -bottom-6 -right-6 w-12 h-12 text-white drop-shadow-lg z-20 animate-bounce" fill="currentColor" viewBox="0 0 24 24"><path d="M4 2v18l5-5h7l-4 4h4l3 5h3l-3-5h4L4 2z"/></svg>
            </div>
        </div>

        <div class="absolute bottom-0 w-full h-32 bg-gradient-to-t from-emerald-900/50 to-transparent z-0 border-b-4 border-emerald-600"></div>
    </header>

    {{-- ABOUT SECTION --}}
    <section id="about" class="py-24 px-6 border-t-4 border-dashed border-gray-700 bg-[#151710]">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 text-gray-300 leading-relaxed text-lg">
                <p>
                    The <strong class="text-yellow-400">Heroes of Innovation Challenge: Ibalong Festival 2026 Edition</strong> is a regional innovation challenge and hackathon that brings together students, startups, developers, designers, researchers, and community innovators from across the Bicol Region.
                </p>
                <p>
                    Anchored on the heroic values of Baltog, Bantong, and Handiong, the challenge invites participants to build technology-enabled, human-centered, and scalable solutions that respond to real development needs and support the vision of a Smart City.
                </p>
                <p class="text-sm border-l-4 border-emerald-500 pl-4 py-2 bg-emerald-900/20">
                    This initiative is led by BiCoRSE under Project REACH, in partnership with government agencies, universities, startup support organizations, and ecosystem partners.
                </p>
            </div>
            <div class="flex justify-center md:justify-end">
                <div class="text-center">
                    <svg class="w-48 h-48 mx-auto text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    <h2 class="font-pixel text-[#0EA5E9] text-xl mt-4 leading-tight">HEROES OF<br>INNOVATION<br><span class="text-sm">CHALLENGE 2026</span></h2>
                </div>
            </div>
        </div>
    </section>

    {{-- HERO PATHWAYS (TRACKS) --}}
    <section id="pathways" class="py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-pixel text-3xl text-yellow-500 mb-4">CHOOSE YOUR PATHWAY</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Every Innovation Team will choose a Hero Pathway that best represents the values, character, and purpose of its proposed innovation.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#24271C] border-4 border-gray-600 p-6 hover:border-orange-500 transition-colors group">
                    <div class="font-pixel text-xl text-orange-500 mb-2">BALTOG</div>
                    <div class="text-xs text-orange-300 font-bold uppercase tracking-widest mb-4">The Pioneer</div>
                    <p class="text-gray-400 text-sm mb-4">"Every great journey begins with the courage to take the first step."</p>
                    <ul class="text-sm text-gray-300 space-y-2 list-disc list-inside">
                        <li>Pioneer new approaches</li>
                        <li>Strengthen resilience & preparedness</li>
                        <li>Protect people and the environment</li>
                    </ul>
                </div>

                <div class="bg-[#24271C] border-4 border-gray-600 p-6 hover:border-blue-500 transition-colors group">
                    <div class="font-pixel text-xl text-blue-500 mb-2">BANTONG</div>
                    <div class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-4">The Strategist</div>
                    <p class="text-gray-400 text-sm mb-4">"Wisdom transforms obstacles into opportunities."</p>
                    <ul class="text-sm text-gray-300 space-y-2 list-disc list-inside">
                        <li>Improve systems & processes</li>
                        <li>Simplify complex challenges</li>
                        <li>Make services more efficient</li>
                    </ul>
                </div>

                <div class="bg-[#24271C] border-4 border-gray-600 p-6 hover:border-green-500 transition-colors group">
                    <div class="font-pixel text-xl text-green-500 mb-2">HANDIONG</div>
                    <div class="text-xs text-green-300 font-bold uppercase tracking-widest mb-4">The Visionary</div>
                    <p class="text-gray-400 text-sm mb-4">"Great leaders do more than solve today's problems. They build tomorrow's communities."</p>
                    <ul class="text-sm text-gray-300 space-y-2 list-disc list-inside">
                        <li>Empower people through knowledge</li>
                        <li>Improve quality of life</li>
                        <li>Create inclusive opportunities</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- TIMELINE --}}
    <section id="timeline" class="py-24 px-6 bg-[#151710] border-y-4 border-dashed border-gray-700">
        <div class="max-w-4xl mx-auto">
            <h2 class="font-pixel text-3xl text-center text-yellow-500 mb-16">QUEST TIMELINE</h2>

            <div class="relative border-l-4 border-dashed border-gray-600 ml-4 md:mx-auto md:border-l-0">
                <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-1 bg-transparent border-l-4 border-dashed border-gray-600 transform -translate-x-1/2"></div>

                @php
                    $timeline = [
                        ['date' => 'JULY 3, 2026', 'title' => 'Launch of Open Call', 'desc' => 'Online', 'icon' => '⭐'],
                        ['date' => 'JULY 3-19, 2026', 'title' => 'Application Period', 'desc' => 'Online (Currently Active)', 'icon' => '📖', 'highlight' => true],
                        ['date' => 'JULY 20, 2026', 'title' => 'Voices of the City', 'desc' => 'Community Hero Stories & Human-Centered Design Workshop', 'icon' => '⚔️'],
                        ['date' => 'JULY 22, 2026', 'title' => 'Hero\'s Response', 'desc' => 'Submission of Concept Proposals', 'icon' => '🚩'],
                        ['date' => 'JULY 27, 2026', 'title' => 'Heroes Cohort', 'desc' => 'Announcement of Selected Teams', 'icon' => '🌻'],
                        ['date' => 'AUGUST 12, 2026', 'title' => 'The Forge', 'desc' => 'Onsite Hackathon Sprint & Prototype Refinement', 'icon' => '💻'],
                        ['date' => 'AUGUST 13, 2026', 'title' => 'Hero\'s Pitch', 'desc' => 'Final Pitching and Awarding (Onsite)', 'icon' => '🏆'],
                    ];
                @endphp

                @foreach($timeline as $index => $item)
                    <div class="relative flex items-center justify-between md:justify-normal w-full mb-12 {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }}">
                        <div class="absolute left-[-20px] md:left-1/2 md:transform md:-translate-x-1/2 w-10 h-10 bg-gray-800 border-4 border-gray-600 rounded-full flex items-center justify-center text-xl z-10">
                            {{ $item['icon'] }}
                        </div>

                        <div class="w-full md:w-5/12 pl-8 md:pl-0 {{ $index % 2 == 0 ? 'md:pl-12' : 'md:pr-12 md:text-right' }}">
                            <div class="p-6 bg-[#24271C] border-4 {{ isset($item['highlight']) ? 'border-yellow-400' : 'border-gray-700' }} shadow-lg">
                                <h3 class="font-pixel text-yellow-500 text-sm md:text-base mb-2">{{ $item['date'] }}</h3>
                                <h4 class="text-white font-bold text-lg mb-1">{{ $item['title'] }}</h4>
                                <p class="text-sm {{ isset($item['highlight']) ? 'text-yellow-400 font-bold' : 'text-gray-400' }}">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA REGISTRATION --}}
    <section id="register" class="py-24 px-6 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="font-pixel text-2xl md:text-4xl text-white mb-6 leading-relaxed">
                ARE YOU READY TO BE A <span class="text-yellow-500">HERO?</span>
            </h2>
            <p class="text-gray-400 mb-10 text-lg">Teams must have 3 to 5 members. Interdisciplinary teams are highly encouraged. Registration closes on July 19, 2026.</p>
            
            <a href="https://bit.ly/hoic2026register" target="_blank" class="btn-pixel inline-block font-pixel text-black bg-yellow-400 hover:bg-yellow-300 px-8 py-5 text-xl border-4 border-white cursor-pointer relative z-10">
                START YOUR QUEST
            </a>
        </div>
    </section>
</div>
