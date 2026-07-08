<div class="bg-iba-light dark:bg-iba-black min-h-screen py-10 sm:py-16 px-4 sm:px-6 transition-colors duration-300">
    <div class="max-w-4xl mx-auto space-y-12">
        
        @if($isSubmitted)
            {{-- SUCCESS SCREEN --}}
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-8 sm:p-16 shadow-[10px_10px_0_0_#5C7914] text-center animate-fade-in-up">
                <div class="mx-auto w-24 h-24 bg-iba-green border-4 border-iba-black dark:border-iba-light flex items-center justify-center shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] mb-8">
                    <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h2 class="font-pixel text-2xl sm:text-4xl text-iba-black dark:text-iba-light uppercase mb-6">APPLICATION RECEIVED!</h2>
                <p class="text-base sm:text-lg text-gray-700 dark:text-gray-300 font-bold mb-8">Your volunteer application has been logged. The Organizing Committee will review your details and reach out to you shortly.</p>
                <a href="{{ route('ibalong.roster') }}" class="btn-retro bg-iba-teal text-white font-pixel px-8 py-4 text-xs sm:text-sm uppercase inline-block border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] hover:translate-y-1 hover:shadow-none transition-all">
                    View The Roster ➔
                </a>
            </div>
        @else
            {{-- HEADER --}}
            <div class="text-center">
                <div class="inline-block bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[8px_8px_0_0_#FF8623] mx-2">
                    <h1 class="font-pixel text-2xl sm:text-5xl text-iba-black dark:text-iba-light uppercase tracking-widest leading-tight">
                        ANSWER THE CALL
                    </h1>
                    <p class="font-bold text-gray-700 dark:text-gray-300 mt-4 text-xs sm:text-base uppercase tracking-wider border-t-4 border-iba-orange pt-4">
                        Volunteer Intake Form • Heroes of Innovation 2026
                    </p>
                </div>
            </div>

            {{-- FORM --}}
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[8px_8px_0_0_#0095AC]">
                <form wire:submit.prevent="submit" class="space-y-6 sm:space-y-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Select a Working Committee <span class="text-iba-red">*</span></label>
                        <select wire:model="committee_id" class="w-full border-4 {{ $errors->has('committee_id') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold text-sm focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors cursor-pointer">
                            <option value="">-- WHERE DO YOU WANT TO SERVE? --</option>
                            @foreach($committees as $committee)
                                <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                            @endforeach
                        </select>
                        @error('committee_id') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Full Name <span class="text-iba-red">*</span></label>
                            <input type="text" wire:model="name" class="w-full border-4 {{ $errors->has('name') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors">
                            @error('name') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">School / Affiliation</label>
                            <input type="text" wire:model="affiliation" class="w-full border-4 border-iba-black dark:border-iba-light p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Email Address <span class="text-iba-red">*</span></label>
                            <input type="email" wire:model="email" class="w-full border-4 {{ $errors->has('email') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors">
                            @error('email') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Mobile Number <span class="text-iba-red">*</span></label>
                            <input type="text" wire:model="mobile_number" class="w-full border-4 {{ $errors->has('mobile_number') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors">
                            @error('mobile_number') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Why do you want to join this committee? <span class="text-iba-red">*</span></label>
                        <textarea wire:model="motivation" rows="4" class="w-full border-4 {{ $errors->has('motivation') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-iba-light transition-colors"></textarea>
                        @error('motivation') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-iba-black dark:text-iba-light uppercase tracking-wider mb-2">Profile Photo (Optional)</label>
                        <div x-data="{ isDropping: false }"
                             x-on:dragover.prevent="isDropping = true"
                             x-on:dragleave.prevent="isDropping = false"
                             x-on:drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));"
                             class="relative flex flex-col items-center justify-center w-full p-8 border-4 border-dashed cursor-pointer transition-all bg-gray-50 dark:bg-gray-900"
                             :class="isDropping ? 'border-iba-teal' : 'border-gray-300 dark:border-gray-700'">

                            <input type="file" x-ref="fileInput" wire:model.live="photo" accept="image/png, image/webp, image/jpeg" class="absolute inset-0 z-50 w-full h-full opacity-0 cursor-pointer">

                            @if ($photo)
                                <div class="flex flex-col items-center pointer-events-none">
                                    <img src="{{ $photo->temporaryUrl() }}" class="h-24 w-24 object-cover border-4 border-iba-black dark:border-iba-light mb-3 grayscale">
                                    <p class="text-sm font-bold text-iba-teal uppercase tracking-wider">Photo Uploaded</p>
                                </div>
                            @else
                                <div class="text-center pointer-events-none" wire:loading.remove wire:target="photo">
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Drag & Drop or <span class="text-iba-teal underline">Click to upload</span></p>
                                </div>
                                <div class="text-center hidden pointer-events-none" wire:loading.class.remove="hidden" wire:target="photo">
                                    <p class="text-sm font-bold text-iba-teal uppercase tracking-wider animate-pulse">Processing Image...</p>
                                </div>
                            @endif
                        </div>
                        @error('photo') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                    </div>

                    {{-- DEVCON PRIVACY & MEDIA CONSENT --}}
                    <div class="pt-4 pb-2">
                        <label class="flex items-start gap-4 cursor-pointer group">
                            <div class="relative flex items-center justify-center shrink-0">
                                <input type="checkbox" wire:model="devcon_consent" class="peer appearance-none w-6 h-6 border-4 border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617] checked:bg-iba-teal cursor-pointer transition-colors shadow-[2px_2px_0_0_#131011] dark:shadow-[2px_2px_0_0_#FFFBF7]">
                                <svg class="absolute w-4 h-4 text-white opacity-0 peer-checked:opacity-100 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span class="text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 leading-relaxed mt-0.5 transition-colors">
                                <span class="text-iba-black dark:text-white uppercase tracking-wider border-b-2 border-iba-teal mr-1">Privacy & Media Consent:</span> 
                                By submitting this form, I consent to the collection, processing, and storage of my personal data by DEVCON and the organizers for event management in accordance with the Data Privacy Act of 2012 (RA 10173). I also grant permission for the organizers to use photographs and video recordings taken during the event for promotional, documentation, and archival purposes. 
                                <a href="https://devcon.ph/standard-privacy-and-safespace-consent/" target="_blank" rel="noopener noreferrer" class="text-iba-teal hover:text-teal-600 dark:hover:text-teal-400 underline ml-1 transition-colors" @click.stop>
                                    Read the DEVCON Standard Privacy and Safe Space Consent here.
                                </a>
                            </span>
                        </label>
                        @error('devcon_consent') 
                            <div class="bg-iba-red text-white font-bold text-xs p-3 mt-3 border-2 border-iba-black uppercase tracking-wider">
                                ⚠ You must agree to the privacy and media consent clause to proceed.
                            </div>
                        @enderror
                    </div>

                    <div class="pt-6 border-t-4 border-iba-black dark:border-iba-light flex justify-end">
                        <button type="submit" class="bg-iba-teal text-white font-pixel px-8 py-5 text-sm sm:text-base uppercase border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7] hover:translate-y-1 hover:shadow-none transition-all" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submit">SUBMIT APPLICATION ➔</span>
                            <span wire:loading wire:target="submit">TRANSMITTING...</span>
                        </button>
                    </div>

                </form>
            </div>
        @endif
    </div>
</div>