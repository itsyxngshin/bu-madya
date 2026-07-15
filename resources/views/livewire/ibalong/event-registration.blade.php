<div class="bg-iba-light dark:bg-iba-black min-h-screen py-10 sm:py-16 px-4 sm:px-6 transition-colors duration-300">
    <div class="max-w-4xl mx-auto space-y-8">

        @if (session()->has('error'))
            <div class="bg-iba-red text-white p-4 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
                <p class="font-bold uppercase tracking-wider">⚠ {{ session('error') }}</p>
            </div>
        @endif

        @if($isSubmitted)
            {{-- SUCCESS SCREEN & TICKET --}}
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[10px_10px_0_0_#FF8623] animate-fade-in-up overflow-hidden">

                {{-- Ticket Header --}}
                <div class="bg-iba-teal p-4 border-b-4 border-iba-black dark:border-iba-light flex justify-between items-center">
                    <h2 class="font-pixel text-white text-sm sm:text-base uppercase tracking-widest">OFFICIAL BOARDING PASS</h2>
                    <span class="bg-iba-black text-white px-3 py-1 text-xs font-bold uppercase">ADMIT ONE</span>
                </div>

                {{-- Ticket Body --}}
                <div class="p-6 sm:p-10 flex flex-col md:flex-row gap-8 items-center md:items-start justify-between">
                    <div class="w-full md:w-2/3 space-y-6">
                        <div>
                            <h3 class="text-3xl font-black text-iba-black dark:text-iba-light uppercase leading-tight">{{ $event->title }}</h3>
                            <p class="text-gray-500 font-bold uppercase tracking-wider mt-1">{{ $event->start_datetime->format('M d, Y') }} @ {{ $event->start_datetime->format('h:i A') }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">ATTENDEE</span>
                                <span class="block text-lg font-bold text-iba-black dark:text-white uppercase truncate">{{ $name }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">ROLE</span>
                                <span class="block text-lg font-bold text-iba-teal uppercase truncate">{{ $role }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">VENUE / PORTAL</span>
                            <span class="block text-sm font-bold text-iba-black dark:text-white">{{ $event->venue_or_link ?: 'To be announced' }}</span>
                        </div>
                    </div>

                    {{-- Ticket QR Code (Chillerlan default Base64) --}}
                    <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-6 border-4 border-iba-black dark:border-iba-light bg-white shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
                        <div class="mb-3 w-40 h-40 flex items-center justify-center">
                            <img src="{{ $qrCodeUri }}" alt="Ticket QR Code" class="w-full h-full object-contain">
                        </div>
                        <span class="font-pixel text-[10px] text-iba-black text-center break-all">{{ $ticket_code }}</span>
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-900 p-4 border-t-4 border-iba-black dark:border-iba-light text-center">
                    <p class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Save a screenshot of this ticket or present it at the terminal gate.</p>
                </div>
            </div>
        @else
            {{-- REGISTRATION FORM --}}
            <div class="text-center mx-2 mb-8">
                <h1 class="font-pixel text-2xl sm:text-4xl text-iba-black dark:text-iba-light uppercase tracking-widest leading-tight">
                    {{ $event->title }}
                </h1>
                <p class="font-bold text-gray-700 dark:text-gray-300 mt-4 text-xs sm:text-sm uppercase tracking-wider inline-block border-b-4 border-iba-orange pb-2">
                    {{ $event->start_datetime->format('F d, Y') }} • {{ $event->venue_or_link ?: 'TBA' }}
                </p>
            </div>

            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[8px_8px_0_0_#0095AC]">

                {{-- Alpine state to watch the role dropdown --}}
                <form wire:submit.prevent="submit" class="space-y-6 sm:space-y-8" x-data="{ selectedRole: @entangle('role') }">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Full Name <span class="text-iba-red">*</span></label>
                            <input type="text" wire:model="name" class="w-full border-4 {{ $errors->has('name') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors">
                            @error('name') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Email Address <span class="text-iba-red">*</span></label>
                            <input type="email" wire:model="email" class="w-full border-4 {{ $errors->has('email') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors">
                            @error('email') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">School / Affiliation</label>
                            <input type="text" wire:model="affiliation" placeholder="e.g. Bicol University" class="w-full border-4 border-iba-black dark:border-iba-light p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Participation Role <span class="text-iba-red">*</span></label>
                            <select wire:model="role" x-model="selectedRole" class="w-full border-4 border-iba-black dark:border-iba-light p-4 font-bold text-sm focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors cursor-pointer">
                                <option value="Audience">Audience / Observer</option>
                                <option value="Team Member">Official Competing Team Member</option>
                                <option value="Facilitator">Facilitator / Staff</option>
                                <option value="VIP">VIP / Mentor</option>
                            </select>
                            @error('role') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- DYNAMIC FIELD: Only shows if "Team Member" is selected --}}
                    <div x-show="selectedRole === 'Team Member'" x-collapse x-cloak>
                        <div class="bg-orange-50 dark:bg-orange-900/20 border-4 border-iba-orange p-6 mt-2 shadow-[4px_4px_0_0_#FF8623]">
                            <label class="block text-sm font-black text-iba-orange uppercase tracking-wider mb-2">Select Your Official Team <span class="text-iba-red">*</span></label>
                            <select wire:model="team_id" class="w-full border-4 {{ $errors->has('team_id') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-900 text-iba-black dark:text-iba-light cursor-pointer">
                                <option value="">-- SELECT YOUR STARTUP / PROJECT --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] font-bold text-gray-500 uppercase mt-2">Note: Only Approved Teams appear on this list.</p>
                            @error('team_id') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t-4 border-iba-black dark:border-iba-light flex justify-end">
                        <button type="submit" class="bg-iba-teal text-white font-pixel px-8 py-5 text-sm sm:text-base uppercase border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7] hover:translate-y-1 hover:shadow-none transition-all" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submit">GENERATE PASS ➔</span>
                            <span wire:loading wire:target="submit">PROCESSING...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
