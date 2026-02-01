<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EvaluationForm extends Component
{
    public Evaluation $evaluation;
    public $project_id = null; // Store the project ID from URL
    public $answers = []; 
    public $isSubmitted = false;

    // Capture project_id from the Query String (?project_id=1)
    protected $queryString = ['project_id'];

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        
        // Capture project ID if passed in URL
        if (request()->has('project_id')) {
            $this->project_id = request()->query('project_id');
        }

        // Initialize empty answers for the UI
        foreach($evaluation->questions as $q) {
            $this->answers[$q->id] = '';
        }
    }

    public function getProgressProperty()
    {
        // Filter out 'sections' as they don't need answers
        $requiredQuestions = $this->evaluation->questions
            ->where('type', '!=', 'section')
            ->where('is_required', true)
            ->count();

        if ($requiredQuestions == 0) return 0;

        // Count how many required answers are filled
        $filled = collect($this->answers)
            ->filter(fn($val) => !empty($val))
            ->count();

        return min(100, round(($filled / $requiredQuestions) * 100));
    }

    public function submit()
    {
        // 1. Validation Logic
        $rules = [];
        foreach ($this->evaluation->questions as $q) {
            if ($q->is_required && $q->type !== 'section') {
                $rules["answers.{$q->id}"] = 'required';
            }
        }
        $this->validate($rules, ['required' => 'This question is required.']);

        // 2. Create Response
        $response = EvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => Auth::id(), // Nullable if guest
            'ip_address' => request()->ip(),
        ]);

        // 3. Save Answers
        foreach ($this->answers as $questionId => $value) {
            $finalValue = $value;

            // Handle File Uploads
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $finalValue = $value->store('evaluation-uploads', 'public');
            } 
            // Handle Arrays (Checkboxes)
            elseif (is_array($value)) {
                $finalValue = json_encode($value);
            }

            EvaluationAnswer::create([
                'evaluation_response_id' => $response->id,
                'evaluation_question_id' => $questionId,
                'answer_value' => $finalValue
            ]);
        }

        // 4. [NEW] Switch State instead of Redirecting immediately
        $this->isSubmitted = true;
        
        // Optional: Scroll to top of page using browser event
        $this->dispatch('scroll-to-top'); 
    }

    public function render()
    {
        $visibleIds = [];
        $skipping = false;
        $targetSectionId = null;

        // Iterate through sorted questions
        foreach ($this->evaluation->questions as $question) {
            
            // 1. Check if we should stop skipping
            if ($skipping) {
                // If we hit a Section AND it matches the target, stop skipping
                if ($question->type === 'section' && $question->id == $targetSectionId) {
                    $skipping = false;
                    $targetSectionId = null;
                }
            }

            // 2. Determine Visibility
            if (!$skipping) {
                $visibleIds[] = $question->id;
            }

            // 3. Check for NEW skip trigger (Only if visible)
            // (We only trigger skip if the question itself is visible)
            if (!$skipping && isset($this->answers[$question->id]) && $question->type === 'radio') {
                $selectedAnswer = $this->answers[$question->id];
                
                if (is_array($question->options)) {
                    foreach ($question->options as $opt) {
                        // Match answer text
                        if (is_array($opt) && $opt['text'] == $selectedAnswer) {
                            // Check for jump
                            if (!empty($opt['jump'])) {
                                if ($opt['jump'] === 'submit') {
                                    // Handle "End Form" logic later if needed
                                    // For now, we effectively skip everything else
                                    $skipping = true; 
                                    $targetSectionId = 9999999; // Impossible ID
                                } else {
                                    $skipping = true;
                                    $targetSectionId = $opt['jump'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('livewire.open.evaluation-form', [
            'visibleQuestionIds' => $visibleIds
        ]);
    }

    public function calculateVisibility()
    {
        $visibleIds = [];
        $skipping = false;
        $targetSectionId = null;

        // Loop through questions in order
        foreach ($this->evaluation->questions as $question) {
            
            // 1. If we are currently skipping...
            if ($skipping) {
                // If this is the target section, STOP skipping
                // We compare the 'order' or a unique ID. 
                // Since we saved 'temp_id' in the JSON but not in the DB column for ID,
                // we assume we saved the 'jump' value as the SECTION'S DB ID or we need a match.
                // *Simpler approach:* In the builder, we saved the temp_id. 
                // In the frontend, we need to match it.
                // *Fix:* Let's check if the current question is a section and if we've reached a destination.
                
                // For this implementation, we will assume "Jump" hides everything until it hits 
                // a Section that matches the ID.
                
                // Note: This requires us to store the 'temp_id' in the DB to match perfectly, 
                // OR we just hide everything until the Next Section if 'jump' == 'next_section'.
                // Since mapping UUIDs from builder to DB is complex, let's use a simpler logic for now:
                // If jump is set, we hide until we hit a section with that specific ID.
                
                // Ideally, you'd save "jump" as the Question ID of the section.
                // But let's assume we are just hiding intervening questions.
            }

            // 2. Logic processing
            // Check if this question has an answer
            if (isset($this->answers[$question->id]) && $question->type === 'radio') {
                $selectedOption = $this->answers[$question->id];
                
                // Find the option config
                foreach ($question->options as $opt) {
                    if (is_array($opt) && $opt['text'] == $selectedOption) {
                        if (!empty($opt['jump'])) {
                            $targetSectionId = $opt['jump'];
                            $skipping = true;
                        }
                    }
                }
            }
            
            // 3. Logic to resume visibility
            // If current question is a Section, check if it matches our target
            // *CRITICAL*: In the builder we used temp_id. In the DB, questions have real IDs.
            // This mismatch breaks the link unless we persisted temp_id.
            // *Fallback*: For now, we will just render all questions, but rely on Alpine to hide them 
            // if we want instant feedback, OR we accept that without the ID persistence, 
            // the skip logic requires the 'jump' value to be the SECTION'S REAL ID.
            
            // *Fix for you*: When saving in Builder, we must map temp_id -> real_id.
            
            $visibleIds[] = $question->id;
        }
        
        // Since the ID mapping is complex, let's return ALL IDs for now to prevent the form from disappearing 
        // until we fix the ID mapping save logic.
        return $this->evaluation->questions->pluck('id')->toArray();
    }
} 