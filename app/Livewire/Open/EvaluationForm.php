<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EvaluationForm extends Component
{
    use WithFileUploads;

    public Evaluation $evaluation;
    public $project_id = null;
    public $answers = []; 
    public $isSubmitted = false;

    protected $queryString = ['project_id'];

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        
        if (request()->has('project_id')) {
            $this->project_id = request()->query('project_id');
        }

        // Initialize empty answers
        foreach($evaluation->questions as $q) {
            $this->answers[$q->id] = '';
        }
    }

    public function getProgressProperty()
    {
        $requiredQuestions = $this->evaluation->questions
            ->where('type', '!=', 'section')
            ->where('is_required', true)
            ->count();

        if ($requiredQuestions == 0) return 0;

        $filled = collect($this->answers)
            ->filter(fn($val) => !empty($val))
            ->count();

        return min(100, round(($filled / $requiredQuestions) * 100));
    }

    public function submit()
    {
        // 1. Validation
        $rules = [];
        foreach ($this->evaluation->questions as $q) {
            // Only validate if visible (we will filter this roughly by checking if the answer is empty on a required field)
            // Ideally, we replicate the visibility logic here, but for now standard validation:
            if ($q->is_required && $q->type !== 'section') {
                $rules["answers.{$q->id}"] = 'required';
            }
        }
        $this->validate($rules, ['required' => 'This question is required.']);

        // 2. Create Response
        $response = EvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
        ]);

        // 3. Save Answers
        foreach ($this->answers as $questionId => $value) {
            // Skip empty answers (optional, prevents clutter)
            if($value === '' || $value === null || $value === []) continue;

            $finalValue = $value;

            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $finalValue = $value->store('evaluation-uploads', 'public');
            } 
            elseif (is_array($value)) {
                $finalValue = json_encode($value);
            }

            EvaluationAnswer::create([
                'evaluation_response_id' => $response->id,
                'evaluation_question_id' => $questionId,
                'answer_value' => $finalValue
            ]);
        }

        $this->isSubmitted = true;
        $this->dispatch('scroll-to-top'); 
    }

    public function render()
    {
        $visibleIds = [];
        $skipping = false;
        $targetSectionId = null;

        // [FIX] Explicitly sort questions by Order
        $sortedQuestions = $this->evaluation->questions->sortBy('order');

        foreach ($sortedQuestions as $question) {
            
            // 1. Check if we should stop skipping
            if ($skipping) {
                if ($question->type === 'section' && $question->id == $targetSectionId) {
                    $skipping = false;
                    $targetSectionId = null;
                }
            }

            // 2. Add to visible list if not skipping
            if (!$skipping) {
                $visibleIds[] = $question->id;
            }

            // 3. Check for Skip Logic Trigger (Radio buttons only)
            if (!$skipping && isset($this->answers[$question->id]) && $question->type === 'radio') {
                $selectedAnswer = $this->answers[$question->id];
                
                if (is_array($question->options)) {
                    foreach ($question->options as $opt) {
                        // Handle array-style options from Builder
                        $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                        $jumpTarget = is_array($opt) ? ($opt['jump'] ?? null) : null;

                        if ($optText == $selectedAnswer && !empty($jumpTarget)) {
                            if ($jumpTarget === 'submit') {
                                $skipping = true; 
                                $targetSectionId = 9999999; 
                            } else {
                                $skipping = true;
                                $targetSectionId = $jumpTarget;
                            }
                        }
                    }
                }
            }
        }

        return view('livewire.open.evaluation-form', [
            'visibleQuestionIds' => $visibleIds,
            'sortedQuestions' => $sortedQuestions // [FIX] Pass sorted collection to view
        ]);
    }
} 