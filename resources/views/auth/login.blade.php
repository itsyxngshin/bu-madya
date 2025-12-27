<x-guest-layout>
    <div class="min-h-screen bg-stone-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
        
        {{-- 1. BRANDING HEADER --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            
            {{-- Logo Icon (Optional) --}}
            <div class="flex justify-center mb-4">
                <a href="/" class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg border border-gray-100 text-red-600 transform hover:scale-105 transition duration-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                </a>
            </div>
            
            {{-- BRAND TYPOGRAPHY --}}
            <h2 class="text-center text-4xl font-heading font-black tracking-tighter text-gray-900">
                BU <span class="text-red-600">MADYA</span>
            </h2>
            <p class="mt-2 text-center text-xs font-bold uppercase tracking-widest text-gray-400">
                Administrative Access
            </p>
        </div>

        {{-- 2. LOGIN CARD --}}
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-2xl sm:rounded-3xl sm:px-10 border border-gray-100 relative overflow-hidden">
                
                {{-- TRICOLOR SCHEME DECORATION (Green -> Yellow -> Red) --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-green-600 via-yellow-400 to-red-600"></div>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">
                            {{ __('Email Address') }}
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required autofocus 
                                value="{{ old('email') }}"
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition text-gray-700 font-medium">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wide">
                                {{ __('Password') }}
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-red-600 hover:text-red-500 transition">
                                    {{ __('Forgot?') }}
                                </a>
                            @endif
                        </div>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition text-gray-700 font-medium">
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" 
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded cursor-pointer transition">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none font-medium">
                            {{ __('Keep me signed in') }}
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-black text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-[1.02] active:scale-95 transition-all duration-200 uppercase tracking-widest">
                            {{ __('Sign In') }}
                        </button>
                    </div>
                </form>

            </div>
            
            {{-- Footer Text --}}
            <div class="mt-8 text-center space-y-2">
                <p class="text-xs text-gray-400 font-medium">
                    &copy; {{ date('Y') }} BU MADYA. All rights reserved.
                </p>
                <div class="flex justify-center gap-2">
                    <div class="h-1 w-8 bg-green-500 rounded-full opacity-50"></div>
                    <div class="h-1 w-8 bg-yellow-400 rounded-full opacity-50"></div>
                    <div class="h-1 w-8 bg-red-600 rounded-full opacity-50"></div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>