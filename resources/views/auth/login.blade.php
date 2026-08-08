<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 font-sans">

        {{-- Main Container (White Background, Extra Padding for the Inset Look) --}}
        <div class="w-full max-w-5xl bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl shadow-gray-900/10 flex flex-col lg:flex-row min-h-[600px] p-2 sm:p-3">

            {{-- LEFT/TOP BOX: Inset Tricolor Branding Panel --}}
            <div class="lg:w-5/12 relative p-8 sm:p-10 flex flex-col justify-between min-h-[250px] lg:min-h-full rounded-3xl sm:rounded-[2rem] overflow-hidden">

                {{-- Solid Tricolor Gradient Background --}}
                <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-yellow-400 to-red-600"></div>

                {{-- Subtle Animated Overlay for Texture --}}
                <div class="absolute inset-0 opacity-20 mix-blend-overlay pointer-events-none">
                    <div class="absolute -top-[20%] -left-[20%] w-[70%] h-[70%] rounded-full bg-white blur-[60px] animate-blob"></div>
                    <div class="absolute bottom-[10%] -right-[20%] w-[80%] h-[80%] rounded-full bg-white blur-[60px] animate-blob animation-delay-2000"></div>
                </div>

                {{-- Top Text Content --}}
                <div class="relative z-10 text-white drop-shadow-md text-left">
                    <p class="text-[10px] sm:text-[11px] font-black uppercase tracking-widest opacity-80 mb-3">
                        Committed to Service
                    </p>
                    <h2 class="text-3xl lg:text-4xl font-heading font-black tracking-tight mb-2 leading-tight">
                        Movement for the <br> Advancement of <br> Youth-led Advocacy
                    </h2>
                    <p class="text-xs font-bold opacity-90 mt-4 max-w-xs leading-relaxed hidden sm:block">
                        Join the central hub for student leadership, project management, and community impact at Bicol University.
                    </p>
                </div>

                {{-- Bottom Social/Partner Links --}}
                <div class="relative z-10 mt-12 text-white drop-shadow-md">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-80 mb-4">Connect with us</p>
                    <div class="flex items-center gap-5">
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/BUMadya" class="hover:text-white/80 transition-transform hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        {{-- Instagram --}}
                        <a href="https://www.instagram.com/bu_madya" class="hover:text-white/80 transition-transform hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        {{-- X (Twitter) --}}
                        <a href="https://www.x.com/bu_madya" class="hover:text-white/80 transition-transform hover:-translate-y-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT/BOTTOM BOX: The Clean Form --}}
            <div class="lg:w-7/12 p-8 sm:p-12 lg:px-20 lg:py-16 flex flex-col justify-center relative">

                <div class="max-w-sm w-full mx-auto">

                    {{-- Header Logo & Welcome Message --}}
                    <div class="mb-10 text-center lg:text-left">
                        <a href="/" class="inline-block mb-6 transition-transform hover:scale-105">
                            <img src="{{ asset('images/MADYA Web Logo1.png') }}" class="h-10 sm:h-12 w-auto mx-auto lg:mx-0" alt="BU MADYA Logo">
                        </a>
                        <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-2">Welcome Back</h2>
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-gray-500">
                            Sign in to your account
                        </p>
                    </div>

                    <x-validation-errors class="mb-5" />

                    @session('status')
                        <div class="mb-5 font-bold text-[10px] sm:text-xs text-green-700 bg-green-50 p-4 rounded-xl border border-green-200 text-center shadow-sm">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    {{-- '@' symbol icon --}}
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                    class="appearance-none block w-full pl-11 pr-4 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm"
                                    placeholder="you@bu-madya.org">
                            </div>
                        </div>

                        {{-- Password with Alpine Toggle --}}
                        <div x-data="{ show: false }">
                            <div class="flex justify-between items-center mb-1.5">
                                <label for="password" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[9px] sm:text-[10px] font-bold text-gray-400 hover:text-blue-500 transition-colors uppercase tracking-widest">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    {{-- Lock icon --}}
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                    class="appearance-none block w-full pl-11 pr-12 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm"
                                    placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-500 focus:outline-none transition-colors">
                                    {{-- Eye icons --}}
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center pt-1">
                            <label class="flex items-center cursor-pointer group">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900 bg-white cursor-pointer transition shadow-sm">
                                <span class="ml-3 text-[10px] sm:text-xs text-gray-500 group-hover:text-gray-900 transition font-bold uppercase tracking-widest select-none">{{ __('Keep me signed in') }}</span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-3">
                            <button type="submit"
                                class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-gray-900/10 text-xs sm:text-sm font-black text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors uppercase tracking-widest">
                                {{ __('Sign In') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>

                    {{-- Footer / Join the Movement --}}
                    <div class="mt-8 relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-100"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 bg-white text-[10px] sm:text-xs font-medium text-gray-500">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="font-bold text-orange-500 hover:text-orange-600 transition-colors ml-1 uppercase tracking-widest">
                                    Join the Movement
                                </a>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-8 w-full text-center pointer-events-none">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} BU MADYA. All rights reserved.
            </p>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 10s infinite alternate ease-in-out; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
</x-guest-layout>
