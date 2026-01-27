<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EvaluationBuilder extends Component
{
    public Evaluation $evaluation;
    public $questions = []; // Array to hold questions state

    protected $rules = [
        'evaluation.title' => 'required|string|max:255',
        'evaluation.description' => 'nullable|string',
        'evaluation.is_active' => 'boolean',
        'questions.*.question_text' => 'required|string',
        'questions.*.type' => 'required|in:text,textarea,radio,likert',
        'questions.*.options' => 'nullable|array',
        'questions.*.is_required' => 'boolean',
    ];

    public function mount(Evaluation $evaluation = null)
    {
        // Use existing evaluation or create a new instance
        $this->evaluation = $evaluation ?? new Evaluation();
        $this->evaluation->is_active = $this->evaluation->is_active ?? true; // Default to active

        // Load existing questions into the array, sorted by order
        if ($this->evaluation->exists) {
            $this->questions = $this->evaluation->questions()
                ->orderBy('order')
                ->get()
                ->toArray();
        } else {
            // Start with one default question if creating new
            $this->questions = [];
        }
    }

    public function addQuestion($type)
    {
        // Define default options based on type
        $defaultOptions = [];
        if ($type === 'likert') {
            $defaultOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        } elseif ($type === 'radio') {
            $defaultOptions = ['Option 1', 'Option 2'];
        }

        $this->questions[] = [
            'id' => null, // null ID indicates a new question
            'type' => $type,
            'question_text' => '',
            'options' => $defaultOptions,
            'is_required' => true,
            'order' => count($this->questions)
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions); // Re-index array
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

    // Livewire Sortable Plugin updates this order array
    public function updateQuestionOrder($list)
    {
        foreach ($list as $item) {
            $this->questions[$item['value']]['order'] = $item['order'];
        }
        
        // Sort the array by order key to keep UI consistent
        usort($this->questions, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
    }

    public function save()
    {
        $this->validate();

        // 1. Save the Form Header
        $this->evaluation->save();
        $this->evaluation->slug = Str::slug($this->evaluation->title);

        // 2. Sync Questions
        // Strategy: Delete all existing and recreate. 
        // (Note: In a production app with live data, you might want to update by ID to preserve answers)
        $this->evaluation->questions()->delete();

        foreach ($this->questions as $index => $q) {
            $this->evaluation->questions()->create([
                'type' => $q['type'],
                'question_text' => $q['question_text'],
                'options' => $q['options'], // Casts to JSON automatically by Model
                'is_required' => $q['is_required'],
                'order' => $index, // Use current array index as order
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