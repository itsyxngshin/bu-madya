<div class="p-6 max-w-4xl mx-auto">
    
    {{-- FORM CARD --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 transition-all duration-300 {{ $editingWaveId ? 'ring-2 ring-orange-400' : '' }}">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h2 class="text-lg font-bold {{ $editingWaveId ? 'text-orange-600' : 'text-gray-800' }}">
                {{ $editingWaveId ? 'Edit Membership Wave' : 'Create Membership Wave' }}
            </h2>
            
            @if($editingWaveId)
                <button wire:click="cancelEdit" class="text-xs font-bold text-gray-400 hover:text-gray-600 uppercase">
                    Cancel Edit
                </button>
            @else
                <span class="text-xs font-medium text-gray-500 bg-white border border-gray-200 px-2 py-1 rounded">New Recruitment Cycle</span>
            @endif
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Inputs --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Academic Year</label>
                    <select wire:model="academic_year_id" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Year</option>
                        @foreach($years as $y) <option value="{{ $y->id }}">{{ $y->name }}</option> @endforeach
                    </select>
                    @error('academic_year_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Wave Name</label>
                    <input wire:model="name" type="text" placeholder="e.g. Wave 1" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                    <input wire:model="start_date" type="date" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('start_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End Date</label>
                    <input wire:model="end_date" type="date" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('end_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="mt-6 flex justify-end gap-3">
                @if($editingWaveId)
                    <button wire:click="cancelEdit" class="text-gray-500 font-bold py-2.5 px-4 rounded-lg text-sm hover:bg-gray-100 transition-all">
                        Cancel
                    </button>
                @endif

                <button wire:click="saveWave" 
                        wire:loading.attr="disabled"
                        class="text-white font-bold py-2.5 px-6 rounded-lg text-sm shadow-md transition-all flex items-center gap-2 {{ $editingWaveId ? 'bg-orange-500 hover:bg-orange-600' : 'bg-indigo-600 hover:bg-indigo-700' }}">
                    <span wire:loading.remove>
                        {{ $editingWaveId ? 'Update Wave' : '+ Create Wave' }}
                    </span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Academic Year</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Wave</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Schedule</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($waves as $wave)
                <tr class="hover:bg-gray-50 transition-colors {{ $editingWaveId === $wave->id ? 'bg-orange-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $wave->academicYear->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">{{ $wave->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                        {{ $wave->start_date->format('M d') }} - {{ $wave->end_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <button wire:click="toggleWave({{ $wave->id }})" 
                                class="relative inline-flex flex-shrink-0 h-5 w-9 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $wave->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                                <span aria-hidden="true" class="{{ $wave->is_active ? 'translate-x-4' : 'translate-x-0' }} pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                            </button>
                        </div>
                    </td>
                    
                    {{-- NEW: Actions Column --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button wire:click="editWave({{ $wave->id }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-full transition-colors" title="Edit Wave">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>