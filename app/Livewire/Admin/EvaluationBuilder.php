<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use Livewire\WithFileUploads;
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
                    // [FIX] Ensure description key exists
                    $arr['description'] = $arr['description'] ?? ''; 
                    return $arr;
                })
            ->toArray();
        } 
        else {
            // Defaults for a new form
            $this->title = ''; 
            $this->slug = '';
            $this->is_active = true;
            $this->questions[] = [
                'id' => null,
                'type' => 'text',
                'question_text' => '',
                'description' => '',
                'options' => [],
                'is_required' => true,
                'order' => 0, 
                'image_path' => null,
                'new_image' => null
            ];
        }
    }
// [NEW] Helper to generate random secure key
    public function generateRandomSlug()
    {
        $this->slug = Str::random(16); // Generates a random 16-char string
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
            'type' => $type,
            'question_text' => '',
            'description' => '',
            'options' => $defaultOptions, // Ensure this array is passed
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
            $this->questions[$item['value']]['order'] = $item['order'];
        }
        
        usort($this->questions, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
    }

    public function save()
    {
        // 1. Validate the local properties
        $this->validate($this->rules());

        if ($this->header_image) {
            $headerPath = $this->header_image->store('evaluation-headers', 'public');
            $this->evaluation->header_image = $headerPath;
        }

        // 2. Transfer data from Properties -> Model
        $this->evaluation->title = $this->title;
        // [NEW] Slug Logic
        if (!empty($this->slug)) {
            // Use the manual or random slug provided
            $this->evaluation->slug = Str::slug($this->slug); // Ensure it's URL safe
        } 
        else {
            // Fallback: Generate from title if empty
            $this->evaluation->slug = Str::slug($this->title);
        }
        $this->evaluation->description = $this->description;
        $this->evaluation->project_id = $this->project_id;
        $this->evaluation->is_active = $this->is_active;

        // 3. Generate Slug logic
        if (empty($this->evaluation->slug)) {
            $this->evaluation->slug = Str::slug($this->title);
        }

        // 4. Save the Header
        $this->evaluation->save();

        // 5. Sync Questions (Delete old, create new)
        $this->evaluation->questions()->delete();

        foreach ($this->questions as $index => $q) {
            
            $imagePath = $q['image_path'] ?? null;

            // Handle New Question Image
            if (isset($q['new_image']) && $q['new_image']) {
                $imagePath = $q['new_image']->store('question-images', 'public');
            }

            $this->evaluation->questions()->create([
                'type' => $q['type'],
                'question_text' => $q['question_text'],
                'description' => $q['description'] ?? null,
                'options' => $q['options'], // Casts to JSON automatically
                'is_required' => $q['is_required'],
                'order' => $index,
                'image_path' => $imagePath // Save the path
            ]);
        }

        session()->flash('success', 'Evaluation form saved successfully!');
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