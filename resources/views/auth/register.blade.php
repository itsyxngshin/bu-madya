<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 font-sans">

        {{-- Main Container --}}
        <div class="w-full max-w-5xl bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl shadow-gray-900/10 flex flex-col lg:flex-row min-h-[600px] p-2 sm:p-3">

            {{-- LEFT/TOP BOX: Inset Tricolor Branding Panel --}}
            <div class="lg:w-5/12 relative p-6 sm:p-10 flex flex-col justify-end min-h-[120px] sm:min-h-[250px] lg:min-h-full rounded-3xl sm:rounded-[2rem] overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-yellow-400 to-red-600"></div>
                <div class="absolute inset-0 opacity-20 mix-blend-overlay pointer-events-none">
                    <div class="absolute -top-[20%] -left-[20%] w-[70%] h-[70%] rounded-full bg-white blur-[60px] animate-blob"></div>
                    <div class="absolute bottom-[10%] -right-[20%] w-[80%] h-[80%] rounded-full bg-white blur-[60px] animate-blob animation-delay-2000"></div>
                </div>

                <div class="relative z-10 text-white drop-shadow-md hidden sm:block text-center lg:text-left">
                    <div class="inline-block px-3.5 py-1.5 mb-4 rounded-full bg-white/20 backdrop-blur-md border border-white/30 shadow-sm">
                        <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-white drop-shadow-sm">
                            Committed to service and reaching communities through advocacy.
                        </p>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-heading font-black tracking-tight mb-3 leading-tight">
                        Movement for the <br> Advancement of <br> Youth-led Advocacy
                    </h2>
                    <p class="text-[10px] lg:text-xs font-black uppercase tracking-widest opacity-90">
                        Bicol University
                    </p>
                </div>

                <div class="relative z-10 mt-10 text-white drop-shadow-md hidden sm:block">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-80 mb-4">Connect with us</p>
                    <div class="flex items-center gap-5 justify-center lg:justify-start">
                        <a href="https://www.facebook.com/BUMadya" class="hover:text-white/80 transition-transform hover:-translate-y-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/bu_madya" class="hover:text-white/80 transition-transform hover:-translate-y-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://www.x.com/bu_madya" class="hover:text-white/80 transition-transform hover:-translate-y-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT/BOTTOM BOX: The Clean Form --}}
            <div class="lg:w-7/12 p-8 sm:p-10 lg:px-16 lg:py-12 flex flex-col justify-center relative">

                <div class="max-w-md w-full mx-auto">

                    <div class="mb-8 text-center lg:text-left">
                        <a href="/" class="inline-block mb-5 transition-transform hover:scale-105">
                            <img src="{{ asset('images/MADYA Web Logo1.png') }}" class="h-10 sm:h-12 w-auto mx-auto lg:mx-0" alt="BU MADYA Logo">
                        </a>
                        <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-2">Join the Movement</h2>
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-gray-500">
                            Create your account to get started
                        </p>
                    </div>

                    <x-validation-errors class="mb-5" />

                    {{-- NEW: Added Alpine data to track if the user is a BU Student --}}
                    <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ isBUStudent: true }">
                        @csrf

                        {{-- Hidden Input to pass the status to the backend --}}
                        <input type="hidden" name="is_bu_student" :value="isBUStudent ? 1 : 0">

                        {{-- NEW: User Categorization Toggle --}}
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">I am registering as...</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" @click="isBUStudent = true"
                                    :class="isBUStudent ? 'bg-blue-50 border-blue-500 text-blue-700 ring-1 ring-blue-500' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50'"
                                    class="px-4 py-3 border rounded-xl text-[11px] sm:text-xs font-bold transition-all flex flex-col items-center justify-center gap-1 shadow-sm focus:outline-none">
                                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                                    <span>BU Student</span>
                                </button>
                                <button type="button" @click="isBUStudent = false"
                                    :class="!isBUStudent ? 'bg-blue-50 border-blue-500 text-blue-700 ring-1 ring-blue-500' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50'"
                                    class="px-4 py-3 border rounded-xl text-[11px] sm:text-xs font-bold transition-all flex flex-col items-center justify-center gap-1 shadow-sm focus:outline-none">
                                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                    <span>External / Guest</span>
                                </button>
                            </div>
                        </div>

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                                    class="appearance-none block w-full pl-11 pr-4 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm"
                                    placeholder="Juan Dela Cruz">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input id="email" type="email" name="email" :value="old('email')" required
                                    class="appearance-none block w-full pl-11 pr-4 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm"
                                    placeholder="you@bu-madya.org">
                            </div>
                        </div>

                        {{-- Academic Details Grid (Conditionally Displayed & Required) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 transition-all duration-300" x-show="isBUStudent" x-transition>
                            <div class="sm:col-span-2">
                                <label for="college_id" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">College</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <select id="college_id" name="college_id" :required="isBUStudent"
                                        class="block w-full pl-11 pr-10 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm cursor-pointer appearance-none bg-none">
                                        <option value="" disabled selected>Select your College</option>
                                        @foreach(\App\Models\College::all() as $college)
                                            <option value="{{ $college->id }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="course" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Course</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <input id="course" type="text" name="course" :value="old('course')" :required="isBUStudent"
                                        class="appearance-none block w-full pl-11 pr-4 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm"
                                        placeholder="e.g. BS IT">
                                </div>
                            </div>

                            <div>
                                <label for="year_level" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Year Level</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                    </div>
                                    <select id="year_level" name="year_level" :required="isBUStudent"
                                        class="block w-full pl-11 pr-10 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm cursor-pointer appearance-none bg-none">
                                        <option value="" disabled selected>Year</option>
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year">3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                        <option value="5th Year">5th Year</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Password Section --}}
                        <div x-data="{ show: false }" class="space-y-4 sm:space-y-5">
                            <div>
                                <label for="password" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                        class="appearance-none block w-full pl-11 pr-12 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm"
                                        placeholder="••••••••">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-500 focus:outline-none transition-colors">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Confirm Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    </div>
                                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                                        class="appearance-none block w-full pl-11 pr-4 py-3 sm:py-3.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-medium shadow-sm"
                                        placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        {{-- Terms and Privacy --}}
                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="flex items-start pt-1">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox" required
                                        class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900 bg-white cursor-pointer transition shadow-sm">
                                </div>
                                <div class="ml-3 text-[10px] sm:text-xs text-gray-500 font-medium leading-snug">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-gray-900 hover:text-blue-500 font-bold transition-colors">'.__('Terms').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-gray-900 hover:text-blue-500 font-bold transition-colors">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        @endif

                        {{-- Submit Button --}}
                        <div class="pt-3">
                            <button type="submit"
                                class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-gray-900/10 text-xs sm:text-sm font-black text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors uppercase tracking-widest">
                                Create Account
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>

                    {{-- Footer / Login Link --}}
                    <div class="mt-8 relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-100"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 bg-white text-[10px] sm:text-xs font-medium text-gray-500">
                                Already have an account?
                                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 transition-colors ml-1 uppercase tracking-widest">
                                    Sign In
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
