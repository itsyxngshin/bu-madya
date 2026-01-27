<div class="min-h-screen bg-gray-100 p-6 font-sans text-gray-900">
    
    <div class="max-w-6xl mx-auto">
        
        {{-- 1. HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('admin.evaluations.index') }}" class="hover:text-orange-500 transition">Evaluations</a>
                    <span>/</span>
                    <span>Results</span>
                </div>
                <h1 class="text-3xl font-black text-gray-900">{{ $evaluation->title }}</h1>
            </div>

            <div class="flex items-center gap-3">
                {{-- Export Button Placeholder --}}
                <button onclick="alert('Export logic (CSV/PDF) goes here!')" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Data
                </button>
            </div>
        </div>

        {{-- 2. OVERVIEW CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Respondents</p>
                    <p class="text-3xl font-black text-gray-900">{{ $evaluation->responses()->count() }}</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Questions</p>
                    <p class="text-3xl font-black text-gray-900">{{ $evaluation->questions()->count() }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl {{ $evaluation->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Status</p>
                    <p class="text-xl font-black text-gray-900">{{ $evaluation->is_active ? 'Active' : 'Closed' }}</p>
                </div>
            </div>
        </div>

        {{-- 3. RESULTS FEED --}}
        <div class="space-y-8">
            @foreach($evaluation->questions as $index => $question)
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-200">
                    
                    {{-- Question Header --}}
                    <div class="mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wide bg-gray-50 px-2 py-1 rounded inline-block mb-2">
                            Question {{ $index + 1 }} • {{ ucfirst($question->type) }}
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $question->question_text }}</h3>
                    </div>

                    {{-- VISUALIZATION LOGIC --}}
                    
                    {{-- A. DATA CHARTS (Radio/Likert) --}}
                    @if(in_array($question->type, ['radio', 'likert']))
                        <div class="space-y-4">
                            @foreach($this->getQuestionStats($question->id) as $stat)
                                <div>
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-bold text-gray-700">{{ $stat['label'] }}</span>
                                        <span class="text-xs font-mono text-gray-500">
                                            <span class="font-bold text-gray-900">{{ $stat['count'] }}</span> responses ({{ $stat['percentage'] }}%)
                                        </span>
                                    </div>
                                    {{-- CSS Progress Bar --}}
                                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-orange-400 to-red-500 rounded-full transition-all duration-1000" 
                                             style="width: {{ $stat['percentage'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    {{-- B. TEXT RESPONSES --}}
                    @elseif(in_array($question->type, ['text', 'textarea']))
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 max-h-80 overflow-y-auto space-y-3">
                            @forelse($this->getTextResponses($question->id) as $answer)
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                    <p class="text-gray-700 text-sm leading-relaxed">"{{ $answer->answer_value }}"</p>
                                    <p class="text-[10px] text-gray-400 mt-2 font-mono text-right">{{ $answer->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-center text-gray-400 text-sm italic py-4">No text responses yet.</p>
                            @endforelse
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

    </div>
</div>