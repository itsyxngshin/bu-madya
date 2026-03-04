<div class="min-h-screen bg-slate-50 flex items-start justify-center p-4 sm:p-6 lg:p-8 font-sans relative overflow-hidden">

    {{-- Subtle Blurred Background (Luma Style) --}}
    @if($event->cover_image)
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover opacity-[0.06] blur-3xl scale-125">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50/90 via-slate-50 to-white/50"></div>
        </div>
    @endif

    <div class="max-w-[72rem] w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 relative z-10 my-4 lg:my-8">

        {{-- LEFT COLUMN: EDITORIAL EVENT DETAILS (Unboxed) --}}
        <div class="lg:col-span-7 flex flex-col pt-2 lg:pt-6">
            
            @if($event->cover_image)
                <div class="w-full aspect-[2/1] md:aspect-video rounded-[2rem] overflow-hidden mb-10 shadow-2xl shadow-gray-200/50 ring-1 ring-black/5">
                    <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover transform hover:scale-105 transition duration-700">
                </div>
            @endif

            @if($event->organizer)
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-white font-black text-sm uppercase shadow-md">
                        {{ substr($event->organizer->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-0.5">Hosted By</p>
                        <p class="text-sm font-bold text-gray-900">{{ $event->organizer->name }}</p>
                    </div>
                </div>
            @endif
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight leading-[1.05] mb-10 drop-shadow-sm">
                {{ $event->title }}
            </h1>

            {{-- Sleek, Unboxed Date & Location info --}}
            <div class="flex flex-col sm:flex-row gap-8 mb-12 pb-10 border-b border-gray-200/60">
                {{-- Date/Time --}}
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-red-100/60 rounded-2xl flex items-center justify-center text-red-600 shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-1.5">Date & Time</p>
                        <p class="text-sm font-bold text-gray-900 leading-snug">
                            {{ $event->start_date ? $event->start_date->format('l, F j, Y') : 'Date TBA' }}
                        </p>
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $event->start_date ? $event->start_date->format('g:i A') : 'Time TBA' }}
                        </p>
                    </div>
                </div>

                {{-- Location --}}
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-blue-100/60 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-1.5">Location</p>
                        <p class="text-sm font-bold text-gray-900 leading-snug pr-4">{{ $event->location }}</p>
                    </div>
                </div>
            </div>

            {{-- DYNAMIC MARKDOWN RENDERER --}}
            <div class="prose prose-slate max-w-none
                prose-headings:font-heading prose-headings:font-black prose-headings:text-gray-900 prose-headings:mb-4
                prose-p:text-gray-600 prose-p:leading-relaxed prose-p:mb-6 prose-p:text-lg
                prose-a:text-red-600 hover:prose-a:text-red-700 prose-a:font-bold prose-a:no-underline hover:prose-a:underline
                prose-img:rounded-3xl prose-img:shadow-xl prose-img:w-full
                prose-ul:list-disc prose-ul:text-gray-600 prose-ul:pl-5
                prose-li:mb-2 prose-li:text-lg pb-12">
                {!! Str::markdown($event->description ?? '') !!}
            </div>
        </div>

        {{-- RIGHT COLUMN: RSVP / TICKETING (Boxed & Sticky) --}}
        <div class="lg:col-span-5 flex flex-col">
            {{-- Sticky positioning keeps the box in view while reading long descriptions --}}
            <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 shadow-2xl shadow-gray-200/80 border border-gray-100 lg:sticky lg:top-8 w-full">

                @if($isRegistered)
                    {{-- STATE: SUCCESS TICKET --}}
                    <div class="text-center animate-fade-in-up">
                        <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 ring-8 ring-green-50/50">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">You're in!</h2>
                        <p class="text-sm text-gray-500 mb-8 font-medium">Please present this ticket at the entrance.</p>

                        {{-- Ticket Design --}}
                        <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden relative text-left">
                            
                            {{-- Top Section (QR) --}}
                            <div class="p-8 flex flex-col items-center border-b-[3px] border-dashed border-gray-200 bg-gray-50/50 relative">
                                {{-- Ticket cutouts (the little half circles on the sides) --}}
                                <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-white rounded-full border border-gray-200"></div>
                                <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-white rounded-full border border-gray-200"></div>
                                
                                <div class="bg-white p-4 rounded-2xl shadow-sm inline-block"
                                     wire:ignore x-data x-init="
                                        $nextTick(() => {
                                            new QRCode($refs.ticketQr, {
                                                text: '{{ $registrationRecord->ticket_code }}',
                                                width: 180, height: 180,
                                                colorDark: '#111827', colorLight: '#ffffff',
                                                correctLevel: QRCode.CorrectLevel.H, dotScale: 0.8
                                            });
                                        })
                                     ">
                                    <div x-ref="ticketQr" class="flex justify-center items-center"></div>
                                </div>
                                <p class="text-sm font-mono font-bold text-gray-400 tracking-[0.2em] mt-4">{{ $registrationRecord->ticket_code }}</p>
                            </div>

                            {{-- Bottom Section (Details) --}}
                            <div class="p-8 bg-white">
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Attendee Name</p>
                                <p class="text-lg font-black text-gray-900 mb-5">{{ $registrationRecord->name }}</p>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Type</p>
                                        <p class="text-xs font-bold text-gray-700">{{ $registrationRecord->classification }}</p>
                                    </div>
                                    @if($registrationRecord->organization_name)
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Affiliation</p>
                                        <p class="text-xs font-bold text-gray-700 truncate" title="{{ $registrationRecord->organization_name }}">
                                            {{ $registrationRecord->organization_name }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- STATE: REGISTRATION FORM --}}
                    <h2 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">Join Event</h2>
                    <p class="text-sm text-gray-500 mb-8">Fill out the form below to secure your spot.</p>

                    @if(!Auth::check())
                        <div class="flex bg-gray-100/80 p-1.5 rounded-2xl mb-8 border border-gray-200/50">
                            <button wire:click="$set('registration_method', 'manual')"
                                    class="flex-1 py-3 text-xs font-bold uppercase tracking-widest rounded-xl transition-all duration-300 {{ $registration_method === 'manual' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-400 hover:text-gray-600' }}">
                                Guest Entry
                            </button>
                            <button wire:click="$set('registration_method', 'account')"
                                    class="flex-1 py-3 text-xs font-bold uppercase tracking-widest rounded-xl transition-all duration-300 {{ $registration_method === 'account' ? 'bg-white shadow-sm text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                                BU MADYA Account
                            </button>
                        </div>
                    @endif

                    <form wire:submit.prevent="register" class="space-y-5">

                        {{-- ACCOUNT LOOKUP MODE --}}
                        @if($registration_method === 'account')
                            @if(!$is_verified)
                                <div class="bg-red-50/50 p-6 rounded-2xl border border-red-100 animate-fade-in-down">
                                    <label class="block text-[10px] font-black text-red-800 uppercase tracking-widest mb-3">System User ID or Email</label>
                                    <div class="flex gap-3">
                                        <input type="text" wire:model="lookup_id" class="w-full rounded-xl border-red-200 bg-white text-sm py-3 px-4 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none" placeholder="e.g. user@example.com">
                                        <button type="button" wire:click="verifyAccount" class="bg-red-600 text-white px-6 rounded-xl font-bold text-sm hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                            <span wire:loading.remove wire:target="verifyAccount">Verify</span>
                                            <span wire:loading wire:target="verifyAccount">...</span>
                                        </button>
                                    </div>
                                    @error('lookup_id') <span class="text-xs text-red-600 mt-2 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            @else
                                {{-- Verified State --}}
                                <div class="bg-green-50 p-6 rounded-2xl border border-green-200 flex items-center justify-between animate-fade-in-down shadow-sm">
                                    <div>
                                        <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Account Verified
                                        </p>
                                        <p class="font-black text-gray-900 text-lg">{{ $name }}</p>
                                        <p class="text-xs font-medium text-gray-600">{{ $email }}</p>
                                    </div>
                                    @if(!Auth::check())
                                        <button type="button" wire:click="$set('is_verified', false)" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition border border-green-200 shadow-sm" title="Change Account">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            @endif

                        {{-- MANUAL ENTRY MODE --}}
                        @else
                            <div class="grid grid-cols-1 gap-5 animate-fade-in-down">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all outline-none font-medium" placeholder="Juan Dela Cruz">
                                    @error('name') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                                    <input type="email" wire:model="email" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all outline-none font-medium" placeholder="juan@example.com">
                                    @error('email') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        {{-- DYNAMIC FORM FIELDS --}}
                        @if($registration_method === 'manual' || $is_verified)

                            <div class="animate-fade-in-up mt-2">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Contact Number</label>
                                <input type="text" wire:model="contact_number" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all outline-none font-medium" placeholder="e.g. 0912 345 6789">
                                @error('contact_number') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div class="animate-fade-in-up pt-4">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Are you a Bicol University Student?</label>
                                <div class="flex bg-gray-100/80 p-1.5 rounded-2xl mb-2 border border-gray-200/50">
                                    <button type="button" wire:click="$set('is_bu_student', true)"
                                            class="flex-1 py-3 text-xs font-bold uppercase tracking-widest rounded-xl transition-all duration-300 {{ $is_bu_student ? 'bg-white shadow-sm text-orange-600' : 'text-gray-400 hover:text-gray-600' }}">
                                        Yes, I am
                                    </button>
                                    <button type="button" wire:click="$set('is_bu_student', false)"
                                            class="flex-1 py-3 text-xs font-bold uppercase tracking-widest rounded-xl transition-all duration-300 {{ !$is_bu_student ? 'bg-white shadow-sm text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                                        No, External
                                    </button>
                                </div>
                            </div>

                            {{-- TIER 1: BU STUDENT --}}
                            @if($is_bu_student)
                                <div class="bg-orange-50/40 p-6 rounded-2xl border border-orange-100/60 space-y-5 animate-fade-in-down mt-2">
                                    <div>
                                        <label class="block text-[10px] font-black text-orange-800 uppercase tracking-widest mb-2">College / Unit</label>
                                        <select wire:model="college_id" class="w-full rounded-xl border-orange-200 bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none font-medium cursor-pointer appearance-none">
                                            <option value="">Select your College...</option>
                                            @foreach($colleges as $college)
                                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('college_id') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-[10px] font-black text-orange-800 uppercase tracking-widest mb-2">Program / Course</label>
                                            <input type="text" wire:model="program" class="w-full rounded-xl border-orange-200 bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none font-medium" placeholder="e.g. BS IT">
                                            @error('program') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-orange-800 uppercase tracking-widest mb-2">Year Level</label>
                                            <select wire:model="year_level" class="w-full rounded-xl border-orange-200 bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none font-medium cursor-pointer appearance-none">
                                                <option value="">Select Year</option>
                                                <option value="1st Year">1st Year</option>
                                                <option value="2nd Year">2nd Year</option>
                                                <option value="3rd Year">3rd Year</option>
                                                <option value="4th Year">4th Year</option>
                                                <option value="5th Year">5th Year</option>
                                            </select>
                                            @error('year_level') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="mt-2 pt-5 border-t border-orange-200/60">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <div class="relative flex items-center justify-center">
                                                <input type="checkbox" wire:model.live="is_representing_org" class="peer w-5 h-5 rounded border-orange-300 text-orange-500 focus:ring-orange-500 transition cursor-pointer appearance-none bg-white checked:bg-orange-500">
                                                <svg class="w-3.5 h-3.5 text-white absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="text-[10px] font-black text-orange-900 uppercase tracking-widest group-hover:text-orange-700 transition">I am representing an Organization</span>
                                        </label>
                                    </div>

                                    @if($is_representing_org)
                                        <div class="bg-white/80 p-5 rounded-2xl border border-orange-200 space-y-5 animate-fade-in-down shadow-sm">
                                            <div>
                                                <label class="block text-[10px] font-black text-orange-800 uppercase tracking-widest mb-2">Organization Name</label>
                                                <input type="text" wire:model="organization_name" class="w-full rounded-xl border-orange-200 bg-white text-sm py-3 px-4 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none font-medium" placeholder="e.g. Red Cross Youth">
                                                @error('organization_name') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-orange-800 uppercase tracking-widest mb-2">Your Position</label>
                                                <input type="text" wire:model="position" class="w-full rounded-xl border-orange-200 bg-white text-sm py-3 px-4 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none font-medium" placeholder="e.g. President">
                                                @error('position') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            {{-- TIER 2: EXTERNAL GUEST --}}
                            @else
                                <div class="bg-blue-50/40 p-6 rounded-2xl border border-blue-100/60 space-y-5 animate-fade-in-down mt-2">
                                    <div>
                                        <label class="block text-[10px] font-black text-blue-800 uppercase tracking-widest mb-2">School / University (Optional)</label>
                                        <input type="text" wire:model="school" class="w-full rounded-xl border-blue-200 bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium" placeholder="e.g. Ateneo de Naga">
                                        @error('school') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-black text-blue-800 uppercase tracking-widest mb-2">Guest Classification</label>
                                        <select wire:model.live="classification" class="w-full rounded-xl border-blue-200 bg-white text-sm py-3.5 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium cursor-pointer appearance-none">
                                            <option value="">Select your classification...</option>
                                            <option value="CSO/NGO Representative">CSO / NGO Representative</option>
                                            <option value="Partner Representative">Partner Organization</option>
                                            <option value="Individual Stakeholder">Individual Stakeholder</option>
                                        </select>
                                        @error('classification') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                    </div>

                                    @if(in_array($classification, ['CSO/NGO Representative', 'Partner Representative']))
                                        <div class="bg-white/80 p-5 rounded-2xl border border-blue-200 space-y-5 animate-fade-in-down shadow-sm">
                                            <div>
                                                <label class="block text-[10px] font-black text-blue-800 uppercase tracking-widest mb-2">Organization Name</label>
                                                <input type="text" wire:model="organization_name" class="w-full rounded-xl border-blue-200 bg-white text-sm py-3 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium" placeholder="e.g. Tarabangan Albay">
                                                @error('organization_name') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-blue-800 uppercase tracking-widest mb-2">Your Position</label>
                                                <input type="text" wire:model="position" class="w-full rounded-xl border-blue-200 bg-white text-sm py-3 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium" placeholder="e.g. Volunteer">
                                                @error('position') <span class="text-xs text-red-500 mt-1.5 block font-bold">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="pt-6">
                                <button type="submit" class="w-full py-4 bg-gray-900 text-white font-black rounded-2xl shadow-xl hover:bg-black hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-sm uppercase tracking-widest flex items-center justify-center gap-2 group">
                                    <span>Secure My Spot</span>
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>

                                <p class="text-[11px] text-gray-400 text-center mt-4 font-medium px-4 leading-relaxed">
                                    By registering, you agree to BU MADYA's privacy policy regarding event data collection and usage.
                                </p>
                            </div>

                        @endif
                    </form>
                @endif

            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
@endpush