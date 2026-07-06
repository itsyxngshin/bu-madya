<div class="min-h-[85vh] flex items-center justify-center px-4 sm:px-6 py-12 bg-iba-light bg-pixel-pattern relative">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute top-10 left-10 text-iba-black/5 font-pixel text-9xl pointer-events-none">H</div>
    <div class="absolute bottom-10 right-10 text-iba-black/5 font-pixel text-9xl pointer-events-none">I</div>

    <div class="w-full max-w-md bg-white border-4 border-iba-black p-8 sm:p-10 shadow-[12px_12px_0_0_#0095AC] relative z-10 animate-fade-in-up">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-iba-orange border-4 border-iba-black shadow-[4px_4px_0_0_#131011] mb-6">
                <svg class="w-8 h-8 text-iba-black" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <h2 class="font-pixel text-xl sm:text-2xl text-iba-black tracking-wide">SYSTEM <span class="text-iba-teal">LOGIN</span></h2>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-2">Authorized Personnel Only</p>
        </div>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="bg-iba-red/10 border-l-4 border-iba-red text-iba-red p-4 mb-6 font-bold text-sm">
                @foreach ($errors->all() as $error)
                    <p>⚠️ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Login Form --}}
        <form wire:submit.prevent="authenticate" class="space-y-6">
            
            <div>
                <label for="email" class="block font-bold text-iba-black mb-2 uppercase tracking-wider text-xs">Email Address</label>
                <div class="relative">
                    <input wire:model="email" id="email" type="email" autocomplete="email" required 
                           class="w-full border-4 border-iba-black p-4 pl-12 font-bold focus:outline-none focus:border-iba-orange bg-iba-light/50 transition-colors">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-iba-black">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block font-bold text-iba-black uppercase tracking-wider text-xs">Password</label>
                </div>
                <div class="relative">
                    <input wire:model="password" id="password" type="password" autocomplete="current-password" required 
                           class="w-full border-4 border-iba-black p-4 pl-12 font-bold focus:outline-none focus:border-iba-orange bg-iba-light/50 transition-colors">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-iba-black">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input wire:model="remember" type="checkbox" class="w-5 h-5 border-2 border-iba-black text-iba-teal focus:ring-0 rounded-none bg-white checked:bg-iba-teal">
                    <span class="text-xs font-bold text-iba-black uppercase group-hover:text-iba-teal transition-colors">Remember Me</span>
                </label>

                {{-- Optional Forgot Password Link --}}
                <a href="#" class="text-xs font-bold text-iba-orange hover:text-iba-red uppercase tracking-wider transition-colors">Recover Access?</a>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-retro w-full bg-iba-teal text-white font-pixel px-6 py-4 text-xs sm:text-sm uppercase flex items-center justify-center gap-2 tracking-widest">
                    INITIALIZE LOGIN
                    <svg wire:loading.remove wire:target="authenticate" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    <svg wire:loading wire:target="authenticate" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
</style>