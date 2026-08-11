<div class="max-w-5xl mx-auto space-y-8 pb-24">

    <div class="bg-iba-black text-white p-6 border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623]">
        <h1 class="text-2xl font-black uppercase tracking-widest">Forge a New Quest</h1>
        <p class="text-xs font-bold text-gray-400 mt-1 uppercase">Define challenges and scoring rubrics for the startup cohorts.</p>
    </div>

    <form wire:submit.prevent="saveQuest" class="space-y-8">
        {{-- Quest Core Details --}}
        <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
            <h2 class="text-lg font-black uppercase mb-4 border-b-4 border-iba-orange pb-2">1. Quest Directives</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Quest Title</label>
                    <input type="text" wire:model="title" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-teal">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Narrative / Description</label>
                    <textarea wire:model="description" rows="3" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-teal resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deadline</label>
                    <input type="datetime-local" wire:model="deadline" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-teal cursor-pointer">
                </div>
                <div class="flex items-center mt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_published" class="w-5 h-5 text-iba-teal border-2 border-iba-black focus:ring-0">
                        <span class="text-sm font-black uppercase tracking-wider">Publish Immediately</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Task Builder --}}
        <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
            <div class="flex justify-between items-center border-b-4 border-iba-teal pb-2 mb-4">
                <h2 class="text-lg font-black uppercase">2. Deliverables & Tasks</h2>
                <button type="button" wire:click="addTask" class="bg-iba-teal text-white text-xs font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">+ Add Task</button>
            </div>

            <div class="space-y-6">
                @foreach($tasks as $index => $task)
                    <div wire:key="task-{{ $index }}" class="p-4 border-2 border-dashed border-gray-300 relative bg-gray-50">
                        <button type="button" wire:click="removeTask({{ $index }})" class="absolute -top-3 -right-3 bg-iba-red text-white w-6 h-6 flex items-center justify-center font-bold border-2 border-iba-black hover:scale-110 transition-transform">X</button>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Task / Question Prompt</label>
                                <input type="text" wire:model="tasks.{{ $index }}.question" class="w-full border-2 border-iba-black p-2 text-sm font-bold focus:outline-none focus:border-iba-orange">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Input Type</label>
                                <select wire:model.live="tasks.{{ $index }}.type" class="w-full border-2 border-iba-black p-2 text-sm font-bold focus:outline-none focus:border-iba-orange uppercase">
                                    <option value="short_text">Short Answer</option>
                                    <option value="long_text">Paragraph</option>
                                    <option value="dropdown">Dropdown Select</option>
                                    <option value="checklist">Multiple Checkboxes</option>
                                    <option value="file">File Upload</option>
                                </select>
                            </div>

                            @if(in_array($task['type'], ['dropdown', 'checklist']))
                                <div class="md:col-span-3">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Options (Comma separated)</label>
                                    <input type="text" wire:model="tasks.{{ $index }}.options" placeholder="Option 1, Option 2, Option 3" class="w-full border-2 border-iba-black p-2 text-sm font-bold focus:outline-none focus:border-iba-orange">
                                </div>
                            @endif

                            @if($task['type'] === 'file')
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Max File Size (MB)</label>
                                    <input type="number" wire:model="tasks.{{ $index }}.max_file_size_mb" class="w-full border-2 border-iba-black p-2 text-sm font-bold focus:outline-none focus:border-iba-orange">
                                </div>
                            @endif

                            <div class="md:col-span-3 flex items-center">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="tasks.{{ $index }}.is_required" class="w-4 h-4 text-iba-black border-2 border-iba-black focus:ring-0">
                                    <span class="text-xs font-bold uppercase">Required Field</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Evaluator Rubric Builder (Collapsible Groups) --}}
        <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-4 border-iba-black pb-2 mb-6 gap-4">
                <h2 class="text-lg font-black uppercase">3. Evaluation Matrix</h2>
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest hidden md:inline-block">Drag criteria blocks to reorganize</span>
                    <button type="button" wire:click="addEvaluationGroup" class="bg-iba-black text-white text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#FF8623] hover:translate-y-0.5 hover:shadow-none transition-all">+ Add Evaluation Group</button>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($evaluationGroups as $gIndex => $group)

                    {{-- Alpine Accordion Wrapper --}}
                    <div wire:key="group-{{ $gIndex }}" x-data="{ expanded: true }" class="border-4 border-iba-black bg-gray-50 shadow-sm relative">

                        {{-- Accordion Header (Click to Toggle) --}}
                        <div @click="expanded = !expanded" class="p-4 bg-gray-200 border-b-4 border-iba-black cursor-pointer hover:bg-gray-300 transition-colors flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                            <div class="w-full md:max-w-sm flex items-center gap-3" @click.stop>
                                {{-- Chevron Toggle Icon --}}
                                <div class="bg-white text-iba-black p-2 border-2 border-iba-black cursor-pointer hover:bg-iba-black hover:text-white transition-colors" @click="expanded = !expanded">
                                    <svg x-show="expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                    <svg x-show="!expanded" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{'hidden': expanded, 'block': !expanded}"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Evaluation Group Name <span class="text-iba-red">*</span></label>
                                    <input type="text" wire:model.blur="evaluationGroups.{{ $gIndex }}.name" placeholder="e.g. Pitch Rubric, Technical Matrix" class="w-full border-2 border-iba-black p-2 text-sm font-black focus:outline-none focus:border-iba-orange uppercase tracking-widest bg-white">
                                    @error("evaluationGroups.{$gIndex}.name") <span class="text-[9px] font-bold text-iba-red uppercase">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex items-center gap-2 w-full md:w-auto" @click.stop>
                                <button type="button" wire:click="addCriteriaToGroup({{ $gIndex }})" class="w-full md:w-auto bg-white text-iba-teal text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black hover:bg-iba-teal hover:text-white shadow-[2px_2px_0_0_#131011] transition-colors shrink-0">
                                    + Add Row
                                </button>
                                <button type="button" wire:click="removeEvaluationGroup({{ $gIndex }})" class="w-full md:w-auto bg-iba-red text-white text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] transition-colors hover:translate-y-0.5 hover:shadow-none shrink-0" title="Purge Group">
                                    Delete Group
                                </button>
                            </div>
                        </div>

                        {{-- Accordion Body (Collapsible Sortable Criteria Container) --}}
                        <div x-show="expanded" class="p-4 sm:p-6" style="display: none;" x-transition>
                            <div
                                class="space-y-8 min-h-[50px] border-2 border-transparent hover:border-dashed hover:border-gray-300 p-2 transition-all"
                                data-group-index="{{ $gIndex }}"
                                x-data
                                x-init="
                                    new Sortable($el, {
                                        group: 'shared-criteria',
                                        animation: 150,
                                        handle: '.drag-handle',
                                        ghostClass: 'opacity-50',
                                        onEnd: function (evt) {
                                            let oldGroup = evt.from.dataset.groupIndex;
                                            let newGroup = evt.to.dataset.groupIndex;
                                            let oldIndex = evt.oldIndex;
                                            let newIndex = evt.newIndex;

                                            if(oldGroup !== undefined && newGroup !== undefined) {
                                                @this.moveCriteria(oldGroup, newGroup, oldIndex, newIndex);
                                            }
                                        }
                                    });
                                "
                            >
                                @forelse($group['criteria'] as $cIndex => $crit)
                                    <div wire:key="crit-{{ $gIndex }}-{{ $cIndex }}" class="p-4 border-2 border-iba-black relative bg-white transition-transform hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#0095AC]">

                                        {{-- Drag Handle --}}
                                        <div class="absolute -top-3 -left-3 drag-handle bg-iba-black text-white w-7 h-7 flex items-center justify-center border-2 border-iba-black cursor-grab hover:bg-iba-orange hover:text-iba-black transition-colors shadow-[2px_2px_0_0_#131011] z-10" title="Drag to reorder or move to another group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                        </div>

                                        {{-- Delete Single Criteria --}}
                                        <button type="button" wire:click="removeCriteriaFromGroup({{ $gIndex }}, {{ $cIndex }})" class="absolute -top-3 -right-3 bg-gray-400 text-white w-6 h-6 flex items-center justify-center font-bold border-2 border-iba-black hover:bg-iba-red hover:scale-110 transition-transform z-10" title="Remove Criteria">X</button>

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 mt-2">
                                            <div class="md:col-span-3">
                                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Criteria Name</label>
                                                <input type="text" wire:model="evaluationGroups.{{ $gIndex }}.criteria.{{ $cIndex }}.name" placeholder="e.g. Feasibility & Viability" class="w-full border-2 border-gray-300 p-2 text-sm font-bold focus:outline-none focus:border-iba-black">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Max Score</label>
                                                <input type="number" wire:model="evaluationGroups.{{ $gIndex }}.criteria.{{ $cIndex }}.max_score" class="w-full border-2 border-gray-300 p-2 text-sm font-black focus:outline-none focus:border-iba-black text-center text-iba-teal">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Rubric Details (Optional)</label>
                                            <textarea wire:model="evaluationGroups.{{ $gIndex }}.criteria.{{ $cIndex }}.description" rows="2" placeholder="Provide scoring guidelines for the judges..." class="w-full border-2 border-gray-300 p-2 text-xs font-bold focus:outline-none focus:border-iba-black resize-none"></textarea>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                                            @foreach(['Outstanding' => 'border-iba-teal', 'Strong' => 'border-iba-green', 'Developing' => 'border-iba-orange', 'Emerging' => 'border-iba-red'] as $level => $color)
                                                @php $lIndex = $loop->index; @endphp
                                                <div wire:key="level-{{ $gIndex }}-{{ $cIndex }}-{{ $lIndex }}" class="bg-gray-50 p-3 border-t-4 {{ $color }} border-x border-b border-gray-200 shadow-sm flex flex-col gap-2">
                                                    <h4 class="text-[10px] font-black uppercase text-center tracking-widest">{{ $level }}</h4>
                                                    <input type="text" wire:model="evaluationGroups.{{ $gIndex }}.criteria.{{ $cIndex }}.levels.{{ $lIndex }}.range" placeholder="Range (e.g. 14-15)" class="w-full border border-gray-300 p-1 text-[10px] text-center font-bold focus:outline-none focus:border-iba-black">
                                                    <textarea wire:model="evaluationGroups.{{ $gIndex }}.criteria.{{ $cIndex }}.levels.{{ $lIndex }}.description" rows="3" placeholder="Description..." class="w-full border border-gray-300 p-1.5 text-[9px] font-medium resize-none focus:outline-none focus:border-iba-black leading-tight"></textarea>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-4 border-2 border-dashed border-gray-300">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Group is empty. Drag criteria here.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full bg-iba-orange text-iba-black text-lg font-black uppercase py-4 border-4 border-iba-black shadow-[6px_6px_0_0_#131011] hover:translate-y-1 hover:shadow-[2px_2px_0_0_#131011] transition-all">Establish Quest Log</button>
    </form>
</div>

{{-- Load SortableJS directly from CDN for drag-and-drop capability --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
