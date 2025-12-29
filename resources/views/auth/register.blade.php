<x-guest-layout>
    <div class="min-h-screen bg-stone-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Blobs (Consistent with site theme) --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        {{-- Header / Logo Area --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/official_logo.png') }}" class="h-16 w-auto mx-auto" alt="BU MADYA Logo">
            </a>
            <h2 class="mt-6 text-3xl font-heading font-black text-gray-900 tracking-tight">
                Join the <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Movement</span>
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Create your account to get started.
            </p>
        </div>

        {{-- Main Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-[2rem] sm:px-10 border border-gray-100 relative">
                
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/3 h-1.5 bg-gradient-to-r from-red-500 to-yellow-400 rounded-b-full"></div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Full Name</label>
                        <div class="relative">
                            <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="Juan Dela Cruz">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" :value="old('email')" required 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="you@bumadya.space">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="new-password" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Terms & Policy --}}
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required 
                                    class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 bg-gray-50 cursor-pointer">
                            </div>
                            <div class="ml-2 text-xs text-gray-500 leading-snug">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-red-600 hover:text-red-800 font-bold">'.__('Terms').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-red-600 hover:text-red-800 font-bold">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition hover:-translate-y-0.5 uppercase tracking-widest">
                            Create Account
                        </button>
                    </div>
                </form>

                {{-- Footer / Login Link --}}
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-bold text-red-600 hover:text-red-500 transition">
                            Log in here
                        </a>
                    </p>
                </div>

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