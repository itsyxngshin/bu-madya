<div class="min-h-screen bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center relative overflow-hidden">
    
    {{-- Orange Background Accent from your mockup --}}
    <div class="absolute inset-0 z-0 flex justify-center">
        <div class="w-[800px] h-full bg-orange-500 rounded-t-[500px] mt-32 shadow-2xl"></div>
    </div>

    <div class="max-w-5xl w-full z-10">
        
        @if($isSubmitted)
            {{-- SUCCESS SCREEN --}}
            <div class="bg-white rounded-2xl shadow-xl p-10 text-center max-w-lg mx-auto transform transition-all">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-3xl font-black text-gray-900 mb-2">Report Submitted</h2>
                <p class="text-gray-500 mb-6">Your incident report has been securely transmitted to the STRAW Head and CSC President. Please save your Case Number for tracking.</p>
                
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-8">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Your Case Number</span>
                    <span class="text-2xl font-black text-orange-600 tracking-wider">{{ $generatedCaseNumber }}</span>
                </div>

                <button wire:click="$set('isSubmitted', false)" class="text-sm font-bold text-gray-500 hover:text-gray-900 transition">
                    Submit Another Report
                </button>
            </div>
        @else
            {{-- INCIDENT FORM --}}
            <form wire:submit.prevent="submitReport" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- LEFT COLUMN --}}
                <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-10">
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <h2 class="text-2xl font-black text-gray-900">Incident Report</h2>
                        <p class="text-sm text-gray-500 mt-1">To report an incident, please provide the following information.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Name Fields --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Incident report issued by: <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <input type="text" wire:model="first_name" class="w-full rounded-lg border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm" placeholder="First Name">
                                    @error('first_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <input type="text" wire:model="middle_name" class="w-full rounded-lg border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm" placeholder="Middle Name">
                                </div>
                                <div>
                                    <input type="text" wire:model="last_name" class="w-full rounded-lg border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm" placeholder="Last Name">
                                    @error('last_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="w-full sm:w-2/3 rounded-lg border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm" placeholder="example@example.com">
                            @error('email') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="phone_number" class="w-full sm:w-2/3 rounded-lg border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm" placeholder="(000) 000-0000">
                            @error('phone_number') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Year and Block --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Year and Block <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="year_and_block" class="w-full sm:w-2/3 rounded-lg border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm" placeholder="e.g. 3rd Year - Block A">
                            @error('year_and_block') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-10 flex flex-col justify-between">
                    
                    <div class="space-y-8">
                        {{-- Nature of Incident --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Nature of Incident <span class="text-red-500">*</span></label>
                            <div class="space-y-2">
                                @foreach(['Bullying', 'Harassment', 'Red Tagging', 'Discrimination', 'Other'] as $nature)
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="radio" wire:model="nature_of_incident" value="{{ $nature }}" class="text-orange-500 focus:ring-orange-500 border-gray-300 w-4 h-4">
                                        <span class="text-sm text-gray-700">{{ $nature }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('nature_of_incident') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Incident Details --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Incident details <span class="text-red-500">*</span></label>
                            <textarea wire:model="incident_details" rows="5" class="w-full rounded-lg border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm resize-none" placeholder="Please describe the incident in detail..."></textarea>
                            @error('incident_details') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- File Upload (Styled like a Drag & Drop zone) --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">File Upload <span class="text-red-500">*</span></label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 hover:bg-gray-50 transition cursor-pointer text-center"
                                 x-data="{ isUploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="isUploading = true"
                                 x-on:livewire-upload-finish="isUploading = false"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                                
                                <input type="file" wire:model="file_upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                
                                <div x-show="!isUploading">
                                    @if($file_upload)
                                        <div class="text-green-500 flex flex-col items-center">
                                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="text-sm font-bold text-gray-900 block truncate w-48">{{ $file_upload->getClientOriginalName() }}</span>
                                            <span class="text-xs text-gray-500">File attached successfully.</span>
                                        </div>
                                    @else
                                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-sm font-bold text-gray-900 block">Browse Files</span>
                                        <span class="text-xs text-gray-500">Drag and drop files here</span>
                                    @endif
                                </div>

                                {{-- Upload Progress Bar --}}
                                <div x-show="isUploading" style="display: none;" class="w-full">
                                    <span class="text-xs font-bold text-orange-500 mb-2 block">Uploading File...</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-orange-500 h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                                    </div>
                                </div>
                            </div>
                            @error('file_upload') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 w-full sm:w-auto">
                            <span wire:loading.remove wire:target="submitReport">Submit Report</span>
                            <span wire:loading wire:target="submitReport">Processing...</span>
                        </button>
                    </div>

                </div>
            </form>
        @endif
    </div>
</div>