<x-guest-layout>
    <div class="min-h-screen bg-stone-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Blobs (Subdued for security context) --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-gray-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        </div>

        {{-- Header / Logo --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/official_logo.png') }}" class="h-16 w-auto mx-auto" alt="BU MADYA Logo">
            </a>
            <h2 class="mt-6 text-2xl font-heading font-black text-gray-900 tracking-tight">
                Security <span class="text-red-600">Check</span>
            </h2>
        </div>

        {{-- Main Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-[2rem] sm:px-10 border border-gray-100 relative overflow-hidden">
                
                {{-- Decorative Top Line (Dark gray for security) --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-gray-700 via-gray-600 to-gray-800"></div>

                {{-- Context Box --}}
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl mb-6 border border-gray-100">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-600 border border-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 leading-snug font-medium">
                        This is a secure area. Please confirm your password before continuing.
                    </p>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Current Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="current-password" autofocus
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transform transition hover:-translate-y-0.5 uppercase tracking-widest">
                            {{ __('Confirm Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tailwind Custom Animation Style --}}
    <style>
        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
</x-guest-layout>