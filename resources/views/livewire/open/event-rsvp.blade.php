<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4 sm:p-8 font-sans relative overflow-hidden">

    {{-- Blurred Background (Luma Style) --}}
    @if($event->cover_image)
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover opacity-20 blur-3xl scale-110">
        </div>
    @endif

    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">

        {{-- LEFT COLUMN: EVENT DETAILS --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100 flex flex-col justify-between">
            <div>
                @if($event->cover_image)
                    <div class="w-full aspect-video rounded-2xl overflow-hidden mb-8 shadow-sm">
                        <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-tight mb-6">
                    {{ $event->title }}
                </h1>

                <div class="space-y-4 mb-8">
                    {{-- Date/Time --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900">
                            {{ $event->start_date ? $event->start_date->format('l, F j, Y') : 'Date TBA' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $event->start_date ? $event->start_date->format('g:i A') : 'Time TBA' }}
                        </p>
                    </div>

                    {{-- Location --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $event->location }}</p>
                            <a href="#" class="text-xs font-bold text-orange-500 hover:underline">View Map</a>
                        </div>
                    </div>
                </div>

                <div class="prose prose-sm text-gray-600">
                    {{ $event->description }}
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: RSVP / TICKETING --}}
        <div class="flex items-center">
            <div class="w-full bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100">

                @if($isRegistered)
                    <div class="text-center animate-fade-in-up">
                        <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-1">You're in!</h2>
                        <p class="text-sm text-gray-500 mb-8">Present this QR code at the entrance.</p>

                        {{-- [FIXED] THE QR CODE --}}
                        <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-md inline-block mb-6 relative"
                             wire:ignore
                             x-data
                             x-init="
                                $nextTick(() => {
                                    new QRCode($refs.ticketQr, {
                                        text: '{{ $registrationRecord->ticket_code }}',
                                        width: 200, height: 200,
                                        colorDark: '#ea580c', colorLight: '#ffffff',
                                        correctLevel: QRCode.CorrectLevel.H, dotScale: 0.8
                                    });
                                })
                             ">
                            <div x-ref="ticketQr" class="flex justify-center items-center"></div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Ticket Code</p>
                            <p class="text-lg font-mono font-bold text-gray-900">{{ $registrationRecord->ticket_code }}</p>
                            <p class="text-sm font-bold text-gray-700 mt-2">{{ $registrationRecord->name }}</p>
                            <p class="text-xs text-gray-500">{{ $registrationRecord->classification }}</p>
                            <p class="text-xs text-gray-500 font-bold mt-1">{{ $registrationRecord->classification }}</p>
                            @if($registrationRecord->organization_name)
                                <p class="text-[10px] text-gray-400">{{ $registrationRecord->position }}, {{ $registrationRecord->organization_name }}</p>
                            @endif
                        </div>
                    </div>

                {{-- STATE: REGISTRATION FORM --}}
                @else
                    <h2 class="text-2xl font-black text-gray-900 mb-6">Join Event</h2>

                    <form wire:submit.prevent="register" class="space-y-4">
                        {{-- Standard Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Full Name</label>
                                <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3" placeholder="Juan Dela Cruz">
                                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Address</label>
                                <input type="email" wire:model="email" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3" placeholder="juan@example.com">
                                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Classification Dropdown --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">I am registering as a...</label>
                            <select wire:model.live="classification" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3 font-medium">
                                <option value="BU Student">Bicol University Student</option>
                                <option value="CSO/NGO Representative">CSO / NGO Representative</option>
                                <option value="Partner Representative">Partner Organization Representative</option>
                                <option value="Individual Stakeholder">Individual Stakeholder</option>
                            </select>
                            @error('classification') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Conditional Student Fields --}}
                        @if($classification === 'BU Student')
                            <div class="bg-orange-50/50 p-4 rounded-xl border border-orange-100 space-y-4 animate-fade-in-down">
                                <div>
                                    <label class="block text-xs font-bold text-orange-800 uppercase mb-2">College / Unit</label>
                                    <select wire:model="college_id" class="w-full rounded-lg border-orange-200 bg-white text-sm py-2 focus:ring-orange-500">
                                        <option value="">Select your College...</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->id }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('college_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-orange-800 uppercase mb-2">Program / Course</label>
                                        <input type="text" wire:model="program" class="w-full rounded-lg border-orange-200 bg-white text-sm py-2 focus:ring-orange-500" placeholder="e.g. BS Information Technology">
                                        @error('program') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-orange-800 uppercase mb-2">Year Level</label>
                                        <select wire:model="year_level" class="w-full rounded-lg border-orange-200 bg-white text-sm py-2 focus:ring-orange-500">
                                            <option value="">Select Year</option>
                                            <option value="1st Year">1st Year</option>
                                            <option value="2nd Year">2nd Year</option>
                                            <option value="3rd Year">3rd Year</option>
                                            <option value="4th Year">4th Year</option>
                                            <option value="5th Year">5th Year</option>
                                        </select>
                                        @error('year_level') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Conditional Organization Fields --}}
                        @if(in_array($classification, ['CSO/NGO Representative', 'Partner Representative']))
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 space-y-4 animate-fade-in-down">
                                <div>
                                    <label class="block text-xs font-bold text-blue-800 uppercase mb-2">Name of Organization / Group</label>
                                    <input type="text" wire:model="organization_name" class="w-full rounded-lg border-blue-200 bg-white text-sm py-2 focus:ring-blue-500" placeholder="e.g. Red Cross Youth, Tarabangan Albay">
                                    @error('organization_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-blue-800 uppercase mb-2">Your Position / Designation</label>
                                    <input type="text" wire:model="position" class="w-full rounded-lg border-blue-200 bg-white text-sm py-2 focus:ring-blue-500" placeholder="e.g. President, Volunteer, Board Member">
                                    @error('position') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="w-full py-4 bg-gray-900 text-white font-black rounded-xl shadow-lg hover:bg-gray-800 transition-all mt-4 text-sm uppercase tracking-widest">
                            Register Now
                        </button>

                        <p class="text-xs text-gray-400 text-center mt-6">
                            By registering, you agree to BU MADYA's privacy policy regarding event data collection.
                        </p>
                    </form>
                @endif

            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
@endpush

