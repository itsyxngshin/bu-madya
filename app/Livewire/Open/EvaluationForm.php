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
        return view('livewire.open.evaluation-form');
    }
} 