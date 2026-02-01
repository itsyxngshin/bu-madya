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
//  Helper to generate random secure key
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

    public function getSectionsProperty()
    {
        return collect($this->questions)
            ->where('type', 'section')
            ->map(function($q) {
                return [
                    'id' => $q['temp_id'], // We use the temp_id as the unique reference
                    'title' => $q['question_text'] ?: 'Untitled Section',
                    'order' => $q['order']
                ];
            })
            ->sortBy('order')
            ->values();
    }

    public function addQuestion($type)
    {
        $defaultOptions = [];
        
        // [UPDATE] Checkbox & Radio now use a structured array for Skip Logic
        if ($type === 'radio' || $type === 'checkbox') {
            $defaultOptions = [
                ['text' => 'Option 1', 'jump' => null], // 'jump' stores the target Section UUID
                ['text' => 'Option 2', 'jump' => null]
            ];
        } 
        // [UPDATE] Likert uses simple strings (usually logic isn't applied to Likert items individually)
        elseif ($type === 'likert') {
            $defaultOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        }

        $this->questions[] = [
            'id' => null,
            'temp_id' => (string) Str::uuid(),
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

    public function addOption($questionIndex)
    {
        // [UPDATE] Add new option with 'jump' structure
        $this->questions[$questionIndex]['options'][] = ['text' => 'New Option', 'jump' => null];
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        // Re-index array to prevent JSON conversion issues
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }
    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
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
        // ... (Validation and Header saving) ...
        
        $this->evaluation->save();

        // 1. Delete removed questions
        // ... (Existing delete logic) ...

        // 2. Create/Update Questions & Build Map
        $tempIdToRealId = []; // Map: 'uuid-123' => 5 (DB ID)

        foreach ($this->questions as $index => $q) {
            $imagePath = $q['image_path'] ?? null;
            if (isset($q['new_image']) && $q['new_image']) {
                $imagePath = $q['new_image']->store('question-images', 'public');
            }

            // Create/Update
            $dbQuestion = $this->evaluation->questions()->updateOrCreate(
                ['id' => $q['id'] ?? null],
                [
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'description' => $q['description'] ?? null,
                    'options' => $q['options'], // We save the raw options first
                    'is_required' => $q['is_required'],
                    'order' => $index,
                    'image_path' => $imagePath 
                ]
            );

            // Store mapping
            if (isset($q['temp_id'])) {
                $tempIdToRealId[$q['temp_id']] = $dbQuestion->id;
            }
        }

        // 3. SECOND PASS: Fix the "Jump" references in options
        // We need to replace the temp_id in the 'jump' field with the real DB ID
        foreach ($this->evaluation->questions as $question) {
            if (in_array($question->type, ['radio']) && is_array($question->options)) {
                $newOptions = [];
                $changed = false;

                foreach ($question->options as $opt) {
                    // Check if this option has a jump target
                    if (is_array($opt) && !empty($opt['jump'])) {
                        // Check if the jump target is a temp_id (UUID)
                        if (isset($tempIdToRealId[$opt['jump']])) {
                            $opt['jump'] = $tempIdToRealId[$opt['jump']]; // Replace with Real ID
                            $changed = true;
                        }
                    }
                    $newOptions[] = $opt;
                }

                if ($changed) {
                    $question->options = $newOptions;
                    $question->save();
                }
            }
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