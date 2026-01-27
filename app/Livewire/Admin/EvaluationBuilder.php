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
    public $questions = [];

    // 1. Defined Rules clearly
    protected $rules = [
        'evaluation.title' => 'required|string|max:255',
        'evaluation.project_id' => 'nullable|integer',
        'evaluation.description' => 'nullable|string',
        'evaluation.is_active' => 'boolean',
        'questions.*.question_text' => 'required|string',
        'questions.*.type' => 'required|in:text,textarea,radio,likert',
        'questions.*.options' => 'nullable|array',
        'questions.*.is_required' => 'boolean',
    ];

    // Keep this to make error messages look nice
    protected $validationAttributes = [
        'evaluation.title' => 'Title',
        'questions.*.question_text' => 'Question text',
    ];

    public function mount(Evaluation $evaluation = null)
    {
        // Handle "Create" vs "Edit" mode
        $this->evaluation = $evaluation ?? new Evaluation();
        
        // Default to active for new forms
        if (!$this->evaluation->exists) {
            $this->evaluation->is_active = true;
        }

        // Load questions if editing
        if ($this->evaluation->exists) {
            $this->questions = $this->evaluation->questions()
                ->orderBy('order')
                ->get()
                ->toArray();
        } else {
            // Start with one default text question
            $this->questions[] = [
                'id' => null,
                'type' => 'text',
                'question_text' => '',
                'options' => [],
                'is_required' => true,
                'order' => 0
            ];
        }
    }

    public function addQuestion($type)
    {
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
            'options' => $defaultOptions,
            'is_required' => true,
            'order' => count($this->questions)
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
        $this->validate();

        // Generate Slug if missing
        if (empty($this->evaluation->slug)) {
            $this->evaluation->slug = Str::slug($this->evaluation->title);
        }

        $this->evaluation->save();

        // Sync Questions (Delete old, create new)
        $this->evaluation->questions()->delete();

        foreach ($this->questions as $index => $q) {
            $this->evaluation->questions()->create([
                'type' => $q['type'],
                'question_text' => $q['question_text'],
                'options' => $q['options'],
                'is_required' => $q['is_required'],
                'order' => $index, 
            ]);
        }

        session()->flash('success', 'Evaluation form saved successfully!');
        return redirect()->route('admin.evaluations.index');
    }

    public function render()
    {
        return view('livewire.admin.evaluation-builder');
    }
}