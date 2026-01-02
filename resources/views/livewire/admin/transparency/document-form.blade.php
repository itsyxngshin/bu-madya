<div class="max-w-4xl mx-auto py-10 px-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.transparency.index') }}" class="text-gray-400 hover:text-red-600 font-bold text-sm transition">
            &larr; Cancel
        </a>
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $is_edit ? 'Edit Document' : 'Upload Document' }}
        </h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form wire:submit="save" class="space-y-6">
            
            {{-- Top Row --}}
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Document Title</label>
                    <input wire:model="title" type="text" class="w-full rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500 transition" placeholder="e.g. Annual Financial Report 2025">
                    @error('title') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>
                    <select wire:model="category_id" class="w-full rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Who can see this?</label>
                    <select wire:model="visibility" class="w-full rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500">
                        <option value="public">Public (Everyone)</option>
                        <option value="auth">Registered Members Only</option>
                        <option value="admin">Administrators Only</option>
                        <option value="director">Directors Only</option>
                    </select>
                    @error('visibility') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                {{-- Academic Year --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Academic Year</label>
                    <select wire:model="academic_year" class="w-full rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500">
                        @foreach($this->years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('academic_year') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- File Upload Area --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">File Upload (PDF or Image)</label>
                
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition relative group">
                    <input type="file" wire:model="file_upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                    
                    @if($file_upload)
                        <div class="text-green-600 font-bold flex flex-col items-center">
                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm">Ready to Upload: {{ $file_upload->getClientOriginalName() }}</span>
                        </div>
                    @elseif($is_edit && $document->file_path)
                        <div class="text-gray-500 flex flex-col items-center">
                            <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-sm font-bold text-gray-900">Current File Exists</span>
                            <span class="text-xs">Click to replace</span>
                        </div>
                    @else
                        <div class="text-gray-400 flex flex-col items-center">
                            <svg class="w-10 h-10 mb-2 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <span class="text-sm font-bold">Click or Drag file here</span>
                            <span class="text-xs mt-1">PDF, JPG, PNG (Max 20MB)</span>
                        </div>
                    @endif

                    {{-- Loading Indicator --}}
                    <div wire:loading wire:target="file_upload" class="absolute inset-0 bg-white/80 z-30 flex items-center justify-center backdrop-blur-sm">
                        <span class="text-red-600 font-bold text-sm animate-pulse">Compressing & Preparing...</span>
                    </div>
                </div>
                @error('file_upload') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Description (Optional)</label>
                <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500"></textarea>
            </div>

            {{-- Published Date --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Publish Date</label>
                <input wire:model="published_date" type="date" class="w-full md:w-1/3 rounded-xl border-gray-200 focus:ring-red-500">
            </div>

            <hr class="border-gray-100">

            {{-- Submit --}}
            <button type="submit" class="w-full py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-red-600 transition shadow-lg flex justify-center items-center gap-2">
                <span wire:loading.remove wire:target="save">
                    {{ $is_edit ? 'Update Document' : 'Upload & Publish' }}
                </span>
                <span wire:loading wire:target="save">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </span>
            </button>

        </form>
    </div>
</div>