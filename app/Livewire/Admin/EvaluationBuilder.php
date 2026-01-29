<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Project; 
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\EvaluationQuestion;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EvaluationBuilder extends Component
{
    use WithFileUploads;
    // The Model (Used for loading/saving, NOT for direct binding)
    public Evaluation $evaluation;

    // 1. Primitive Properties (These catch your form input 100% of the time)
    public $title = '';
    public $slug = '';
    public $description = '';
    public $project_id = null;
    public $is_active = true;
    public $header_image; // Temporary upload for header
    public $existing_header_image; // To show existing header
    
    // Questions Array
    public $questions = [];
    public $available_projects = [];

    // 2. Rules defined as a METHOD (Crucial for Livewire security)
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255', // Validates $this->title
            'slug' => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('evaluations', 'slug')->ignore($this->evaluation->id)
            ],
            'project_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'questions' => 'array',
            'questions.*.question_text' => 'required|string',
            // Note: added 'section' to the allowed types
            'questions.*.type' => 'required|in:text,textarea,radio,likert,section,file', 
            'questions.*.new_image' => 'nullable|image|max:2048', // For question images
            'questions.*.options' => 'nullable|array',
            'questions.*.is_required' => 'boolean',
            'questions.*.description' => 'nullable|string|max:1000',
        ];
    }

    protected $validationAttributes = [
        'questions.*.question_text' => 'Question text',
    ];

    public function mount(Evaluation $evaluation = null)
    {
        $this->available_projects = Project::where('status', '!=', 'Draft')
            ->orderBy('title')
            ->select('id', 'title') // Optimize query
            ->get();

        // 1. Setup the Model
        $this->evaluation = $evaluation ?? new Evaluation();

        // 2. Fill the Primitive Properties from the Model (if editing)
        if ($this->evaluation->exists) {
            $this->title = $this->evaluation->title;
            $this->slug = $this->evaluation->slug;
            $this->description = $this->evaluation->description;
            $this->project_id = $this->evaluation->project_id;
            $this->is_active = $this->evaluation->is_active;

            // Load existing questions
            $this->questions = $this->evaluation->questions()
                ->orderBy('order')
                ->get()
                ->map(function($q) {
                    $arr = $q->toArray();
                    $arr['new_image'] = null;
                    $arr['description'] = $arr['description'] ?? '';
                    // [FIX] Assign a unique temporary ID for the frontend builder
                    $arr['temp_id'] = (string) Str::uuid(); 
                    return $arr;
                })
                ->toArray();
        } 
        else {
            // Defaults for a new form
            $this->title = ''; 
            $this->slug = '';
            $this->is_active = true;
            $this->questions = [];
        }
    }
// [NEW] Helper to generate random secure key
    public function generateRandomSlug()
    {
        $this->slug = Str::random(16); // Generates a random 16-char string
    }

    #[On('confirmed-reset')] 
    public function resetResponses()
    {
        if (!$this->evaluation->exists) return;

        $responseIds = $this->evaluation->responses()->pluck('id');

        if ($responseIds->isEmpty()) {
            // Send SweetAlert warning back to frontend
            $this->dispatch('swal:modal', [
                'type' => 'info',
                'title' => 'No Data',
                'text' => 'There are no responses to clear.'
            ]);
            return;
        }

        // Delete logic
        \App\Models\EvaluationAnswer::whereIn('evaluation_response_id', $responseIds)->delete();
        $this->evaluation->responses()->delete();

        // Send SweetAlert success back to frontend
        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Deleted!',
            'text' => 'All responses have been permanently cleared.'
        ]);
    }

    public function moveQuestionUp($index)
    {
        if ($index > 0) {
            // Swap items in the array
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index - 1];
            $this->questions[$index - 1] = $temp;
            
            // Update their internal order numbers
            $this->reindexQuestions();
        }
    }

    public function moveQuestionDown($index)
    {
        if ($index < count($this->questions) - 1) {
            // Swap items
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index + 1];
            $this->questions[$index + 1] = $temp;
            
            // Update order numbers
            $this->reindexQuestions();
        }
    }

    private function reindexQuestions()
    {
        // Reset the 'order' property for all items based on their new array position
        foreach ($this->questions as $idx => &$question) {
            $question['order'] = $idx;
        }
    }

    public function addQuestion($type)
    {
        // 1. Define Default Options strictly
        $defaultOptions = [];
        
        if ($type === 'likert') {
            $defaultOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        } elseif ($type === 'radio') {
            $defaultOptions = ['Option 1', 'Option 2'];
        }

        $this->questions[] = [
            'id' => null,
            'temp_id' => (string) Str::uuid(), // [FIX] Generate UUID for new items
            'type' => $type,
            'question_text' => '',
            'description' => '',
            'options' => $defaultOptions,
            'is_required' => ($type !== 'section'),
            'order' => count($this->questions),
            'image_path' => null,
            'new_image' => null
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = 'New Option';
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function updateQuestionOrder($list)
    {
        foreach ($list as $item) {
            $value = $item['value']; // This is the temp_id
            $order = $item['order'];

            // Find the question with this temp_id and update its order
            foreach ($this->questions as $key => $q) {
                if ($q['temp_id'] === $value) {
                    $this->questions[$key]['order'] = $order;
                    break; 
                }
            }
        }
        
        usort($this->questions, fn($a, $b) => $a['order'] <=> $b['order']);
    }

    public function save()
    {
        $this->validate();

        // 1. Handle Header Image
        if ($this->header_image) {
            $headerPath = $this->header_image->store('evaluation-headers', 'public');
            $this->evaluation->header_image = $headerPath;
        }

        // 2. Save Main Evaluation Details
        $this->evaluation->title = $this->title;
        // Logic to preserve existing slug or generate new one
        if (!empty($this->slug)) {
            $this->evaluation->slug = Str::slug($this->slug);
        } elseif (empty($this->evaluation->slug)) {
            $this->evaluation->slug = Str::slug($this->title);
        }
        
        $this->evaluation->description = $this->description;
        $this->evaluation->project_id = $this->project_id;
        $this->evaluation->is_active = $this->is_active;
        $this->evaluation->save();

        // 3. Collect IDs of questions currently in the builder
        // We use this to know which questions to KEEP
        $existingIds = collect($this->questions)->pluck('id')->filter()->toArray();

        // 4. SYNC QUESTIONS (The Fix)
        
        // A. Delete questions that were REMOVED from the builder
        // We wrap this in a try-catch because if you try to delete a specific question 
        // that has answers, the DB will still stop you (which is good!).
        try {
            $this->evaluation->questions()
                ->whereNotIn('id', $existingIds)
                ->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            // Optional: Flash a warning that some questions couldn't be deleted due to existing answers
            session()->flash('warning', 'Some removed questions could not be deleted because they already have user responses.');
        }

        // B. Update existing or Create new questions
        foreach ($this->questions as $index => $q) {
            
            $imagePath = $q['image_path'] ?? null;

            // Handle New Image Upload
            if (isset($q['new_image']) && $q['new_image']) {
                $imagePath = $q['new_image']->store('question-images', 'public');
            }

            // [CRITICAL CHANGE] Use updateOrCreate instead of create
            $this->evaluation->questions()->updateOrCreate(
                ['id' => $q['id'] ?? null], // Look up by ID (if null, it creates a new one)
                [
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'description' => $q['description'] ?? null,
                    'options' => $q['options'], 
                    'is_required' => $q['is_required'],
                    'order' => $index,
                    'image_path' => $imagePath 
                ]
            );
        }

        session()->flash('success', 'Evaluation updated successfully!');
        return redirect()->route('admin.evaluations.index');
    }

    public function delete()
    {
        if($this->evaluation->exists) {
            $this->evaluation->delete();
            return redirect()->route('admin.evaluations.index');
        }
    }

    public function render()
    {
        return view('livewire.admin.evaluation-builder');
    }
}