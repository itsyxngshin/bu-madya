<x-guest-layout>
    <div class="min-h-screen bg-stone-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Blobs --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        {{-- Header / Logo --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/official_logo.png') }}" class="h-16 w-auto mx-auto" alt="BU MADYA Logo">
            </a>
            <h2 class="mt-6 text-2xl font-heading font-black text-gray-900 tracking-tight">
                Two-Factor <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Auth</span>
            </h2>
        </div>

        {{-- Main Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md" x-data="{ recovery: false }">
            <div class="bg-white py-8 px-4 shadow-xl rounded-[2rem] sm:px-10 border border-gray-100 relative overflow-hidden">
                
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-gray-700 via-gray-600 to-gray-800"></div>

                {{-- Instructions (Dynamic) --}}
                <div class="mb-6 flex items-start gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="shrink-0 pt-0.5">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-600 border border-gray-100">
                            {{-- Shield Icon --}}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <div class="text-xs text-gray-600 leading-relaxed font-medium">
                        <span x-show="! recovery">
                            {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                        </span>
                        <span x-show="recovery" x-cloak>
                            {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                        </span>
                    </div>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('two-factor.login') }}">
                    @csrf

                    {{-- Code Input (Primary) --}}
                    <div class="mt-4" x-show="! recovery">
                        <label for="code" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">{{ __('Authentication Code') }}</label>
                        <div class="relative">
                            <input id="code" class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-lg tracking-widest font-mono text-center font-bold" 
                                   type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" placeholder="000 000" />
                        </div>
                    </div>

                    {{-- Recovery Code Input (Secondary) --}}
                    <div class="mt-4" x-cloak x-show="recovery">
                        <label for="recovery_code" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">{{ __('Recovery Code') }}</label>
                        <div class="relative">
                            <input id="recovery_code" class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm font-mono" 
                                   type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" placeholder="Enter recovery code" />
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-4">
                        {{-- Submit Button --}}
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transform transition hover:-translate-y-0.5 uppercase tracking-widest">
                            {{ __('Log in') }}
                        </button>

                        {{-- Toggle Links --}}
                        <div class="text-center">
                            <button type="button" class="text-xs font-bold text-gray-400 hover:text-red-600 transition flex items-center justify-center gap-1 mx-auto"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                {{ __('Use a recovery code') }}
                            </button>

                            <button type="button" class="text-xs font-bold text-gray-400 hover:text-red-600 transition flex items-center justify-center gap-1 mx-auto"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                {{ __('Use an authentication code') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tailwind Custom Animation Style --}}
    <style>
        [x-cloak] { display: none !important; }
        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
</x-guest-layout>