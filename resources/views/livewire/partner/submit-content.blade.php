<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Submit Content</h1>
        <p class="text-gray-500 mt-2">Request to publish an announcement or a promotional spotlight on the BU MADYA landing page.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 bg-gray-50">
            <button wire:click="$set('submissionType', 'announcement')" class="flex-1 py-4 px-6 text-center font-bold text-sm uppercase tracking-widest transition {{ $submissionType === 'announcement' ? 'text-red-600 border-b-2 border-red-600 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                Announcement Bar
            </button>
            <button wire:click="$set('submissionType', 'spotlight')" class="flex-1 py-4 px-6 text-center font-bold text-sm uppercase tracking-widest transition {{ $submissionType === 'spotlight' ? 'text-blue-600 border-b-2 border-blue-600 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Spotlight Banner
            </button>
        </div>

        <form wire:submit.prevent="submit" class="p-6 md:p-8 space-y-6">
            
            {{-- Title Field (Shared) --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Title</label>
                <input type="text" wire:model="title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="{{ $submissionType === 'announcement' ? 'e.g. CLASS SUSPENSION' : 'e.g. Youth Summit 2026' }}">
                @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- ANNOUNCEMENT SPECIFIC FIELDS --}}
            @if($submissionType === 'announcement')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Announcement Type</label>
                    <select wire:model="announcement_type_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">-- Select Type --</option>
                        @foreach($announcementTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('announcement_type_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                    <textarea wire:model="message" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="A brief message explaining the announcement..."></textarea>
                    @error('message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            @endif

            {{-- SPOTLIGHT SPECIFIC FIELDS --}}
            @if($submissionType === 'spotlight')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Spotlight Category</label>
                    <select wire:model="spotlight_category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Select Category --</option>
                        @foreach($spotlightCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('spotlight_category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Landscape Banner Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl {{ $image ? 'bg-gray-50' : '' }}">
                        <div class="space-y-1 text-center">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="mx-auto h-32 object-cover rounded shadow mb-3">
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" wire:model="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB (21:9 ratio recommended)</p>
                        </div>
                    </div>
                    @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Optional Link / Call to Action</label>
                    <input type="url" wire:model="link" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://...">
                    @error('link') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            @endif

            <hr class="border-gray-200">

            {{-- Scheduling (Shared) --}}
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-3">Scheduling (Optional)</h3>
                <p class="text-xs text-gray-500 mb-3">Leave blank to start immediately upon approval and run forever.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Start Date & Time</label>
                        <input type="datetime-local" wire:model="start_at" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500">
                        @error('start_at') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">End Date & Time</label>
                        <input type="datetime-local" wire:model="end_at" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500">
                        @error('end_at') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-8 rounded-xl shadow-md transition flex items-center">
                    <span wire:loading.remove wire:target="submit">Submit Request</span>
                    <span wire:loading wire:target="submit">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
