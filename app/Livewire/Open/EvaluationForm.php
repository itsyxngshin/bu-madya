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
    public $answers = []; // Stores user's answers: [question_id => value]

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
        // 1. Dynamic Validation Logic
        $rules = [];
        $messages = [];

        foreach($this->evaluation->questions as $q) {
            if($q->is_required) {
                $rules["answers.{$q->id}"] = 'required';
                $messages["answers.{$q->id}.required"] = "This question is required.";
            }
        }
        
        $this->validate($rules, $messages);

        // 2. Create the Response Header (Who submitted it?)
        $response = EvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => Auth::id(), // Can be null if allowing anonymous
            'project_id' => $this->project_id, // Link to specific project if available
        ]);

        // 3. Save the Individual Answers
        foreach($this->answers as $questionId => $value) {
    
            // Check if the answer is an UploadedFile object (from Livewire)
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                // Store it and save the path string
                $path = $value->store('evaluation-uploads', 'public');
                $finalValue = $path;
            } else {
                $finalValue = is_array($value) ? json_encode($value) : $value;
            }

            EvaluationAnswer::create([
                'evaluation_response_id' => $response->id,
                'evaluation_question_id' => $questionId,
                'answer_value' => $finalValue
            ]);
        }

        // 4. Reset & Notify
        session()->flash('success', 'Thank you! Your evaluation has been submitted.');
        
        // Optional: Redirect to a thank you page or back to the index
        return redirect()->route('welcome'); 
    }

    public function render()
    {
        return view('livewire.open.evaluation-form');
    }
} 