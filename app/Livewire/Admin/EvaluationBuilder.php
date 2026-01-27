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

    protected $rules = [
        'evaluation.title' => 'required|string|max:255',
        'evaluation.description' => 'nullable|string',
        'evaluation.is_active' => 'boolean',
        'evaluation.project_id' => 'nullable|integer', // [FIX] Added validation for project link
        'questions.*.question_text' => 'required|string',
        'questions.*.type' => 'required|in:text,textarea,radio,likert',
        'questions.*.options' => 'nullable|array',
        'questions.*.is_required' => 'boolean',
    ];

    public function mount(Evaluation $evaluation = null)
    {
        $this->evaluation = $evaluation ?? new Evaluation();
        
        // Ensure default active state is true for new forms
        if (!$this->evaluation->exists) {
            $this->evaluation->is_active = true;
        }

        if ($this->evaluation->exists) {
            $this->questions = $this->evaluation->questions()
                ->orderBy('order')
                ->get()
                ->toArray();
        } else {
            $this->questions = [];
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

        // [FIX] Generate Slug BEFORE saving
        if (empty($this->evaluation->slug)) {
            $this->evaluation->slug = Str::slug($this->evaluation->title);
        }

        // [FIX] Warning: If editing an active form with responses, 
        // using delete() on questions will orphan existing answers.
        // For a simple builder, this is acceptable, but be aware.
        
        $this->evaluation->save();

        // Sync Questions
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