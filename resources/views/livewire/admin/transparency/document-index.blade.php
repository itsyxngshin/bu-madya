<div class="p-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Transparency Board</h1>
            <p class="text-sm text-gray-500">Manage financial reports, memos, and files.</p>
        </div>
        <a href="{{ route('admin.transparency.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-600 transition shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Upload Document
        </a>
    </div>

    {{-- Stats Cards (Quick Overview) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase">Total Files</p>
                <p class="text-2xl font-black text-gray-900">{{ \App\Models\TransparencyDocument::count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase">Total Downloads</p>
                <p class="text-2xl font-black text-gray-900">{{ \App\Models\TransparencyDocument::sum('downloads_count') }}</p>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search by title..." class="w-full md:w-1/3 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500">
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 font-bold">Document</th>
                    <th class="p-4 font-bold">Category</th>
                    <th class="p-4 font-bold">Academic Year</th>
                    <th class="p-4 font-bold text-center">Downloads</th>
                    <th class="p-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($documents as $doc)
                    <tr class="hover:bg-gray-50/50 transition group">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold">
                                    {{ strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">{{ $doc->title }}</p>
                                    <p class="text-xs text-gray-400">{{ $doc->published_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs font-bold bg-{{ $doc->category->color ?? 'gray' }}-50 text-{{ $doc->category->color ?? 'gray' }}-600">
                                {{ $doc->category->name }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-600 font-medium">{{ $doc->academic_year }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $doc->downloads_count }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.transparency.edit', $doc->id) }}" class="text-gray-400 hover:text-blue-600 font-bold text-xs uppercase">Edit</a>
                            <button wire:click="delete({{ $doc->id }})" wire:confirm="Are you sure?" class="text-gray-400 hover:text-red-600 font-bold text-xs uppercase">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400 text-sm">No documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</div>