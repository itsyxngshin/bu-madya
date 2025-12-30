<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pillar;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class ThePillarsManager extends Component
{
    use WithFileUploads;

    public $pillars;
    public $isModalOpen = false;
    public $editingPillarId = null;

    // Form Data
    public $title, $description, $image;
    
    // Nested Structure: [ ['id'=>null, 'text'=>'?', 'options'=>[ ['id'=>null, 'label'=>'Yes', 'color'=>'green'] ]] ]
    public $questions = []; 

    public function mount()
    {
        $this->loadPillars();
    }

    public function loadPillars()
    {
        // Eager load nested relationships for efficiency
        $this->pillars = Pillar::with('questions.options')->latest()->get();
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'image', 'editingPillarId']);
        // Default: 1 Question with Yes/No/Abstain
        $this->questions = [
            [
                'id' => null,
                'text' => '',
                'options' => [
                    ['id' => null, 'label' => 'Yes', 'color' => 'green'],
                    ['id' => null, 'label' => 'No', 'color' => 'red'],
                    ['id' => null, 'label' => 'Abstain', 'color' => 'gray']
                ]
            ]
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->editingPillarId = $id;
        $pillar = Pillar::with('questions.options')->find($id);

        $this->title = $pillar->title;
        $this->description = $pillar->description;
        
        // Map existing structure to array
        $this->questions = $pillar->questions->map(function($q) {
            return [
                'id' => $q->id,
                'text' => $q->question_text,
                'options' => $q->options->map(fn($o) => [
                    'id' => $o->id,
                    'label' => $o->label,
                    'color' => $o->color
                ])->toArray()
            ];
        })->toArray();

        $this->isModalOpen = true;
    }

    // --- Dynamic Form Handlers ---

    public function addQuestion()
    {
        $this->questions[] = [
            'id' => null,
            'text' => '',
            'options' => [
                ['id' => null, 'label' => 'Yes', 'color' => 'green'],
                ['id' => null, 'label' => 'No', 'color' => 'red']
            ]
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addOption($qIndex)
    {
        $this->questions[$qIndex]['options'][] = ['id' => null, 'label' => '', 'color' => 'gray'];
    }

    public function removeOption($qIndex, $oIndex)
    {
        unset($this->questions[$qIndex]['options'][$oIndex]);
        $this->questions[$qIndex]['options'] = array_values($this->questions[$qIndex]['options']);
    }

    // --- Save Logic ---

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.label' => 'required|string',
        ]);

        // 1. Handle Pillar
        $data = [
            'title' => $this->title,
            'description' => $this->description,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('pillars', 'public');
        }

        $pillar = $this->editingPillarId 
            ? Pillar::find($this->editingPillarId) 
            : new Pillar();
        
        $pillar->fill($data);
        $pillar->save();

        // 2. Handle Questions (Sync Logic)
        $keepQuestionIds = [];

        foreach ($this->questions as $qData) {
            $question = $pillar->questions()->updateOrCreate(
                ['id' => $qData['id']], 
                ['question_text' => $qData['text']]
            );
            $keepQuestionIds[] = $question->id;

            // 3. Handle Options (Sync Logic)
            $keepOptionIds = [];
            foreach ($qData['options'] as $oData) {
                $option = $question->options()->updateOrCreate(
                    ['id' => $oData['id']],
                    ['label' => $oData['label'], 'color' => $oData['color']]
                );
                $keepOptionIds[] = $option->id;
            }
            // Delete removed options
            $question->options()->whereNotIn('id', $keepOptionIds)->delete();
        }

        // Delete removed questions
        $pillar->questions()->whereNotIn('id', $keepQuestionIds)->delete();

        $this->isModalOpen = false;
        $this->loadPillars();
        session()->flash('message', 'Pillar saved successfully.');
    }

    public function toggleStatus($id)
    {
        $p = Pillar::find($id);
        $p->is_active = !$p->is_active;
        $p->save();
        $this->loadPillars();
    }

    public function delete($id)
    {
        Pillar::find($id)->delete(); // Cascades delete questions/options/votes
        $this->loadPillars();
    }

    public function render()
    {
        return view('livewire.director.the-pillars-manager'); // Ensure you use your admin layout
    }
}