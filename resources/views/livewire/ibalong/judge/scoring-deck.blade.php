<div class="max-w-7xl mx-auto pb-24">
    
    <div class="bg-iba-black text-white p-6 border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-widest">The Weighing of the Gift</h1>
            <p class="text-xs font-bold text-iba-teal mt-1 uppercase">{{ $submission->team->team_name ?? 'Unknown Cohort' }} • {{ $submission->quest->title }}</p>
        </div>
        <a href="{{ route('ibalong.admin.quests.index') }}" class="text-xs font-black uppercase text-gray-400 hover:text-white border-2 border-transparent hover:border-gray-400 px-3 py-1 transition-all">&larr; Return</a>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 mb-8 shadow-[4px_4px_0_0_#131011]">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- LEFT COLUMN: Team's Deliverables --}}
        <div class="space-y-6">
            <h2 class="text-lg font-black uppercase border-b-4 border-iba-black pb-2">Cohort Submission</h2>
            
            @foreach($submission->quest->tasks as $task)
                @php 
                    $answer = $submission->answers->where('task_id', $task->id)->first(); 
                @endphp
                
                <div class="bg-white border-4 border-iba-black shadow-[4px_4px_0_0_#131011] p-5">
                    <h3 class="text-xs font-black text-iba-teal uppercase tracking-widest mb-3">{{ $task->question }}</h3>
                    
                    @if(!$answer)
                        <p class="text-sm font-bold text-gray-400 italic">No response provided.</p>
                    @elseif($task->type === 'file')
                        <a href="{{ Storage::url($answer->file_path) }}" target="_blank" class="inline-flex items-center gap-2 bg-iba-black text-white text-xs font-black uppercase px-4 py-2 border-2 border-transparent hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            View Transmitted File
                        </a>
                    @elseif($task->type === 'checklist')
                        @php $choices = json_decode($answer->answer_text, true); @endphp
                        <ul class="list-disc list-inside text-sm font-bold text-iba-black space-y-1">
                            @foreach((array)$choices as $choice)
                                <li>{{ $choice }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm font-bold text-iba-black whitespace-pre-wrap leading-relaxed">{{ $answer->answer_text }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- RIGHT COLUMN: The Rubric & Scoring --}}
        <div class="space-y-6">
            <h2 class="text-lg font-black uppercase border-b-4 border-iba-orange pb-2">Scoring Matrix</h2>
            
            <form wire:submit.prevent="lockScores" class="space-y-6">
                @foreach($submission->quest->criteria as $crit)
                    <div class="bg-gray-50 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] p-5">
                        <div class="flex justify-between items-center border-b-2 border-dashed border-gray-300 pb-2 mb-4">
                            <h3 class="text-sm font-black uppercase">{{ $crit->name }}</h3>
                            <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-2 py-1">Max: {{ $crit->max_score }} Pts</span>
                        </div>
                        
                        <p class="text-xs font-bold text-gray-600 mb-4">{{ $crit->description }}</p>

                        {{-- Tiers Visualizer --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-6">
                            @foreach($crit->rubric_levels as $level)
                                @php
                                    $color = match($level['degree']) {
                                        'Outstanding' => 'border-iba-teal text-iba-teal',
                                        'Strong' => 'border-iba-green text-iba-green',
                                        'Developing' => 'border-iba-orange text-iba-orange',
                                        'Emerging' => 'border-iba-red text-iba-red',
                                        default => 'border-gray-500 text-gray-500'
                                    };
                                @endphp
                                <div class="bg-white p-2 border-l-4 border-2 border-iba-black {{ $color }} flex flex-col h-full">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[9px] font-black uppercase tracking-widest">{{ $level['degree'] }}</span>
                                        <span class="text-[10px] font-black">{{ $level['range'] }}</span>
                                    </div>
                                    <p class="text-[9px] font-bold text-gray-600 leading-tight flex-1">{{ $level['description'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Scoring Inputs --}}
                        <div class="flex flex-col sm:flex-row gap-4 bg-white p-4 border-2 border-iba-black">
                            <div class="w-full sm:w-1/3">
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Assigned Marks</label>
                                <input type="number" step="0.5" wire:model="scores.{{ $crit->id }}" max="{{ $crit->max_score }}" min="0" class="w-full border-2 border-iba-black p-3 text-lg font-black text-center focus:outline-none focus:border-iba-orange text-iba-orange">
                                @error("scores.{$crit->id}") <span class="text-[10px] font-black text-iba-red uppercase">⚠ Required</span> @enderror
                            </div>
                            <div class="w-full sm:w-2/3">
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Council Notes / Feedback (Optional)</label>
                                <textarea wire:model="feedback.{{ $crit->id }}" rows="2" class="w-full border-2 border-iba-black p-2 text-xs font-bold focus:outline-none focus:border-iba-orange resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pt-4 sticky bottom-4 z-10">
                    <button type="submit" class="w-full bg-iba-black text-iba-orange text-lg font-black uppercase tracking-widest py-4 border-4 border-iba-black shadow-[6px_6px_0_0_#FF8623] hover:translate-y-1 hover:shadow-none transition-all">Lock Council Scores</button>
                </div>
            </form>
        </div>
    </div>
</div>