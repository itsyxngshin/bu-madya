<div class="min-h-screen bg-stone-50 font-sans text-gray-900 selection:bg-red-200 selection:text-red-900">
    
    {{-- BACKGROUND BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-stone-50/90"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    </div>

    {{-- NAV --}}
    <nav class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-6">
        <a href="{{ route('linkages.index') }}" class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Network
        </a>
        <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
            Partner <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">With Us</span>
        </span>
        <div class="w-8"></div>
    </nav>

    <div class="relative z-10 max-w-7xl mx-auto px-6 pt-32 pb-24 grid lg:grid-cols-2 gap-16 items-start">
        
        {{-- LEFT COLUMN: THE PITCH --}}
        <div class="space-y-12">
            
            <div>
                <h1 class="font-heading font-black text-5xl md:text-6xl text-gray-900 leading-[1.1] mb-6">
                    Let's create impact <br> <span class="text-red-600">together.</span>
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed font-serif">
                    We are always looking for strategic partners in government, civil society, and the private sector to amplify our advocacy for youth empowerment and sustainable development.
                </p>
            </div>

            {{-- Why Partner? --}}
            <div class="grid sm:grid-cols-2 gap-6">
                <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white/50 shadow-sm">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Access to Youth Leaders</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Connect with over 500+ student leaders across the Bicol Region through our network.</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white/50 shadow-sm">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700 mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Data-Driven Advocacy</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Collaborate on research and policy papers backed by our academic committees.</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white/50 shadow-sm">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Sustainable Impact</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Align your CSR or development goals with specific United Nations SDGs.</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white/50 shadow-sm">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Brand Visibility</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Co-branding opportunities in our major university-wide and regional events.</p>
                </div>
            </div>

            {{-- Contact --}}
            <div class="pt-8 border-t border-gray-200">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Direct Contact</h4>
                <p class="text-sm font-bold text-gray-900">Director for External Affairs</p>
                <a href="mailto:bu.madya2025@gmail.com" class="text-sm text-red-600 hover:underline">bu.madya2025@gmail.com</a>
            </div>

        </div>

        {{-- RIGHT COLUMN: THE FORM --}}
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 md:p-10 relative overflow-hidden">
            
            {{-- Success Message Overlay --}}
            @if (session()->has('success'))
            <div class="absolute inset-0 bg-white/95 backdrop-blur-sm z-50 flex flex-col items-center justify-center text-center p-8 transition-all">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6 animate-bounce">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="font-heading font-black text-3xl text-gray-900 mb-2">Proposal Sent!</h3>
                <p class="text-gray-500 mb-8 max-w-xs mx-auto">{{ session('success') }}</p>
                <a href="{{ route('linkages.index') }}" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-full hover:bg-gray-800 transition">Return to Linkages</a>
            </div>
            @endif

            <h2 class="font-heading font-black text-2xl text-gray-900 mb-6">Submit Proposal</h2>

            <form wire:submit.prevent="submit" class="space-y-6">
                
                {{-- 1. Org Details --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Organization Name</label>
                        <input wire:model="orgName" type="text" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition placeholder-gray-400" placeholder="e.g. ABC Foundation">
                        @error('orgName') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Contact Person</label>
                        <input wire:model="contactPerson" type="text" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition placeholder-gray-400" placeholder="Full Name">
                        @error('contactPerson') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email Address</label>
                        <input wire:model="email" type="email" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition placeholder-gray-400" placeholder="email@company.com">
                        @error('email') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Partnership Type</label>
                        <select wire:model="type" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-gray-700">
                            <option>Event Partnership</option>
                            <option>Long-term MOU</option>
                            <option>Sponsorship</option>
                            <option>Academic Exchange</option>
                            <option>CSR Implementation</option>
                        </select>
                    </div>
                </div>

                {{-- 2. Proposal Body --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Proposal Title</label>
                    <input wire:model="title" type="text" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition placeholder-gray-400 font-bold text-gray-900" placeholder="e.g. Joint Coastal Cleanup Drive">
                    @error('title') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Message / Abstract</label>
                    <textarea wire:model="message" rows="5" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition placeholder-gray-400 leading-relaxed" placeholder="Tell us about your proposal objectives and how we can collaborate..."></textarea>
                    @error('message') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>

                {{-- 3. File Upload --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Attach File (PDF/DOC)</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition group cursor-pointer">
                        <input wire:model="file" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <div class="flex flex-col items-center justify-center">
                            @if($file)
                                <svg class="w-8 h-8 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-bold text-gray-900">{{ $file->getClientOriginalName() }}</span>
                            @else
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-red-500 transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <span class="text-sm text-gray-500">Click to upload or drag file here</span>
                                <span class="text-[10px] text-gray-400 mt-1">Max 10MB</span>
                            @endif
                        </div>
                    </div>
                    @error('file') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-red-600 to-yellow-500 text-white font-black uppercase tracking-widest rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition transform">
                        <span wire:loading.remove>Send Proposal</span>
                        <span wire:loading>Sending...</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>