<div x-data="documentViewer()" class="max-w-5xl mx-auto space-y-8 pb-24">
    
    {{-- ALPINE SCRIPT FOR DOCUMENT VIEWER --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('documentViewer', () => ({
                viewerOpen: false,
                viewerUrl: '',
                viewerTitle: '',
                isNativeViewer: false,
                
                openViewer(fileUrl, fileName) {
                    this.viewerTitle = fileName;
                    const extension = fileName.split('.').pop().toLowerCase();
                    
                    if (extension === 'pdf' || extension === 'txt' || extension.match(/(jpg|jpeg|png|gif|webp)$/i)) {
                        // Browsers can handle PDFs, Text, and Images natively
                        this.isNativeViewer = true;
                        this.viewerUrl = fileUrl;
                    } else if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(extension)) {
                        // Route Office files through Microsoft Office Online Viewer
                        this.isNativeViewer = false;
                        this.viewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(fileUrl);
                    } else {
                        alert('Inline preview is not available for this file type. Please download it instead.');
                        return;
                    }
                    
                    this.viewerOpen = true;
                }
            }));
        });
    </script>

    <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623]">
        <h1 class="text-2xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Resource Vault</h1>
        <p class="text-sm font-bold text-gray-500 mt-1">Access essential hackathon files, datasets, and templates here.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ADMIN CREATE SECTION --}}
    @if($isAdmin)
        <div x-data="{ open: false }" class="bg-gray-100 dark:bg-gray-800 border-4 border-iba-black p-6">
            <button @click="open = !open" class="flex items-center justify-between w-full text-left font-black uppercase text-iba-black dark:text-white">
                <span>➕ Upload New Resource Pack</span>
                <span x-text="open ? '−' : '+'"></span>
            </button>
            
            <div x-show="open" x-collapse class="mt-6 pt-6 border-t-4 border-iba-black">
                <form wire:submit.prevent="createResourceGroup" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Title</label>
                            <input type="text" wire:model="title" required class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none focus:border-iba-orange">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Schedule Drop (Optional)</label>
                            <input type="datetime-local" wire:model="availableAt" class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none focus:border-iba-orange">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Description / Instructions</label>
                        <textarea wire:model="description" rows="2" class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none focus:border-iba-orange resize-none"></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="flex-1 w-full">
                            <input type="file" wire:model="uploads" multiple class="w-full border-4 border-iba-black bg-white text-xs font-bold p-1 cursor-pointer file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-iba-black file:text-white file:font-black file:uppercase">
                            <div wire:loading wire:target="uploads" class="text-[10px] font-bold text-iba-orange mt-1 animate-pulse uppercase">Uploading...</div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <input type="checkbox" wire:model="isVisible" id="visible" class="w-5 h-5 border-2 border-iba-black cursor-pointer">
                            <label for="visible" class="text-[10px] font-black uppercase text-iba-black cursor-pointer">Visible to Cohort</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-iba-orange text-iba-black font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all" wire:loading.attr="disabled">Upload Pack</button>
                </form>
            </div>
        </div>
    @endif

    {{-- RESOURCE BARS --}}
    <div class="space-y-6">
        @forelse($resourceGroups as $group)
            <div wire:key="group-{{ $group->id }}" x-data="{ expanded: false }" class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
                
                {{-- ADMIN OVERLAY --}}
                @if($isAdmin)
                    <div class="bg-gray-200 dark:bg-gray-800 border-b-4 border-iba-black px-4 py-2 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <span class="text-[9px] font-black uppercase tracking-widest {{ $group->is_visible ? 'text-iba-green' : 'text-iba-red' }}">
                            {{ $group->is_visible ? '👁 Visible' : '🚫 Hidden' }}
                            @if($group->available_at && $group->available_at > now())
                                | ⏳ Drops at: {{ $group->available_at->format('M d, Y h:i A') }}
                            @endif
                        </span>
                        <div class="flex gap-4">
                            <button wire:click="toggleVisibility({{ $group->id }})" class="text-[9px] font-black uppercase text-gray-500 hover:text-iba-black">Toggle Vis</button>
                            <button wire:click="editGroup({{ $group->id }})" class="text-[9px] font-black uppercase text-iba-teal hover:text-teal-700">Edit</button>
                            <button wire:click="deleteGroup({{ $group->id }})" class="text-[9px] font-black uppercase text-iba-red hover:text-red-700" onclick="confirm('Are you sure you want to delete this entire pack?') || event.stopImmediatePropagation()">Delete</button>
                        </div>
                    </div>
                @endif

                {{-- EDIT MODE --}}
                @if($editingGroupId === $group->id)
                    <div class="p-6">
                        <form wire:submit.prevent="updateGroup" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Title</label>
                                    <input type="text" wire:model="editTitle" required class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none focus:border-iba-orange">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Schedule Drop</label>
                                    <input type="datetime-local" wire:model="editAvailableAt" class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none focus:border-iba-orange">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Description</label>
                                <textarea wire:model="editDescription" rows="2" class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none focus:border-iba-orange resize-none"></textarea>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" wire:model="editIsVisible" id="editVisible" class="w-4 h-4 border-2 border-iba-black cursor-pointer">
                                <label for="editVisible" class="text-[10px] font-black uppercase text-iba-black cursor-pointer">Visible to Cohort</label>
                            </div>

                            {{-- Manage Existing Files --}}
                            @if($group->files->count() > 0)
                                <div class="bg-gray-50 p-4 border-4 border-iba-black mt-4">
                                    <label class="block text-[10px] font-black uppercase text-gray-500 mb-2 border-b-2 border-dashed border-gray-300 pb-2">Existing Files</label>
                                    <div class="space-y-2">
                                        @foreach($group->files as $file)
                                            <div class="flex justify-between items-center bg-white border-2 border-iba-black p-2">
                                                <span class="text-xs font-bold truncate">{{ $file->file_name }}</span>
                                                <div class="flex items-center gap-3">
                                                    @php
                                                        $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                                        $isViewable = in_array($ext, ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'jpg', 'png', 'jpeg']);
                                                    @endphp
                                                    @if($isViewable)
                                                        <button type="button" @click="openViewer('{{ url(Storage::url($file->file_path)) }}', '{{ addslashes($file->file_name) }}')" class="text-[10px] font-black uppercase text-iba-teal hover:underline shrink-0">Preview</button>
                                                    @endif
                                                    <button type="button" wire:click="deleteFile({{ $file->id }})" class="text-[10px] font-black uppercase text-iba-red hover:underline shrink-0">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Add New Files --}}
                            <div class="mt-4">
                                <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Add More Files</label>
                                <input type="file" wire:model="newUploads" multiple class="w-full border-4 border-iba-black bg-white text-xs font-bold p-1 cursor-pointer file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-iba-black file:text-white file:font-black file:uppercase">
                                <div wire:loading wire:target="newUploads" class="text-[10px] font-bold text-iba-orange mt-1 animate-pulse uppercase">Uploading...</div>
                            </div>

                            <div class="flex gap-4 justify-end pt-4 border-t-2 border-dashed border-gray-300 mt-4">
                                <button type="button" wire:click="cancelEdit" class="text-xs font-black uppercase tracking-widest text-gray-500 hover:text-iba-black transition-colors px-4 py-2">Cancel</button>
                                <button type="submit" class="bg-iba-teal text-white font-black px-6 py-2.5 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Save Changes</button>
                            </div>
                        </form>
                    </div>

                {{-- NORMAL VIEW --}}
                @else
                    <div @click="expanded = !expanded" class="p-5 cursor-pointer flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                        <div>
                            <h2 class="text-lg font-black text-iba-black dark:text-white uppercase">{{ $group->title }}</h2>
                            @if($group->description)
                                <p class="text-xs font-bold text-gray-600 dark:text-gray-400 mt-1">{{ $group->description }}</p>
                            @endif
                        </div>
                        <div class="shrink-0 ml-4 bg-iba-black text-white w-8 h-8 flex items-center justify-center font-black">
                            <span x-text="expanded ? '−' : '+'"></span>
                        </div>
                    </div>

                    <div x-show="expanded" x-collapse class="border-t-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-900 p-5 space-y-3">
                        @forelse($group->files as $file)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-2 border-dashed border-gray-300 dark:border-gray-700 p-3 bg-white dark:bg-gray-800">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <svg class="w-6 h-6 text-iba-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $file->file_name }}</span>
                                    <span class="text-[10px] font-black text-gray-400 ml-2 shrink-0">{{ $file->file_size }}</span>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    @php
                                        $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                        $isViewable = in_array($ext, ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'jpg', 'png', 'jpeg']);
                                    @endphp
                                    
                                    @if($isViewable)
                                        <button type="button" @click="openViewer('{{ url(Storage::url($file->file_path)) }}', '{{ addslashes($file->file_name) }}')" class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">View</button>
                                    @endif
                                    
                                    <a href="{{ Storage::url($file->file_path) }}" download class="bg-iba-teal text-white text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all text-center">Download</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs font-bold text-gray-500 uppercase">No files in this pack.</p>
                        @endforelse
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black border-dashed p-12 text-center shadow-sm">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No resources are currently available.</p>
            </div>
        @endforelse
    </div>

    {{-- DOCUMENT VIEWER MODAL --}}
    <div x-show="viewerOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-2 sm:p-6">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" @click="viewerOpen = false"></div>
        
        <div class="relative w-full max-w-6xl h-full max-h-[90vh] bg-white dark:bg-[#1A1617] border-4 border-iba-black shadow-[12px_12px_0_0_#0095AC] flex flex-col z-10 animate-fade-in-up">
            
            {{-- Viewer Header --}}
            <div class="flex justify-between items-center p-3 sm:p-4 border-b-4 border-iba-black bg-iba-teal text-white shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <h3 class="font-black uppercase tracking-widest text-xs sm:text-sm truncate" x-text="viewerTitle"></h3>
                </div>
                <button @click="viewerOpen = false" class="shrink-0 w-8 h-8 flex items-center justify-center border-2 border-iba-black bg-iba-red hover:bg-red-600 transition-colors ml-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Viewer Iframe Body --}}
            <div class="flex-1 bg-gray-200 dark:bg-gray-900 relative">
                {{-- Loader (Visible while iframe loads) --}}
                <div x-show="!isNativeViewer" class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-6 text-center">
                    <svg class="w-8 h-8 text-iba-orange animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 animate-pulse">Connecting to Office Viewer...</span>
                    <span class="text-[9px] font-bold text-gray-400">(If this fails to load, the file must be hosted on a live server, or you can just download it).</span>
                </div>
                
                <iframe :src="viewerUrl" class="absolute inset-0 w-full h-full border-none z-10 bg-white" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>