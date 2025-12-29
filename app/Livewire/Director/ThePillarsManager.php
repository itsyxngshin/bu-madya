<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Poll;
use App\Models\PollOption;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class ThePillarsManager extends Component
{
    use WithFileUploads;

    public $polls;
    public $isModalOpen = false;
    
    // Tracks if we are editing (ID exists) or creating (Null)
    public $editingPollId = null; 

    // Form Fields
    public $title, $question, $description, $image, $expires_at;
    public $options = []; 

    public function mount()
    {
        $this->loadPolls();
        $this->resetForm();
    }

    public function loadPolls()
    {
        $this->polls = Poll::with('options')->latest()->get();
    }

    public function resetForm()
    {
        $this->reset(['title', 'question', 'description', 'image', 'expires_at', 'editingPollId']);
        $this->options = [
            ['id' => null, 'label' => 'Yes', 'color' => 'green'],
            ['id' => null, 'label' => 'No', 'color' => 'red'],
            ['id' => null, 'label' => 'Abstain', 'color' => 'gray']
        ];
    }

    // 1. LOAD DATA FOR EDITING
    public function edit($id)
    {
        $this->resetForm(); // Clear old data first
        $this->editingPollId = $id;
        
        $poll = Poll::with('options')->find($id);

        $this->title = $poll->title;
        $this->question = $poll->question;
        $this->description = $poll->description;
        
        // Map existing options to the form array
        $this->options = $poll->options->map(function($option) {
            return [
                'id' => $option->id, // Important: Track ID so we update instead of create
                'label' => $option->label,
                'color' => $option->color
            ];
        })->toArray();

        $this->isModalOpen = true;
    }

    public function addOption()
    {
        $this->options[] = ['id' => null, 'label' => '', 'color' => 'gray'];
    }

    public function removeOption($index)
    {
        // Optional: Add logic here to prevent deleting options that already have votes
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function save()
    {
        $this->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*.label' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // A. Handle Image Upload (Only if new image provided)
        $imagePath = $this->image ? $this->image->store('pillars', 'public') : null;

        if ($this->editingPollId) {
            // === UPDATE MODE ===
            $poll = Poll::find($this->editingPollId);
            
            $data = [
                'title' => $this->title,
                'question' => $this->question,
                'description' => $this->description,
            ];
            
            // Only update image if a new one was uploaded
            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }

            $poll->update($data);

            // Sync Options
            // 1. Get IDs of options currently in the form
            $keepOptionIds = array_filter(array_column($this->options, 'id'));
            
            // 2. Delete options that were removed from the form
            $poll->options()->whereNotIn('id', $keepOptionIds)->delete();

            // 3. Update existing or Create new
            foreach ($this->options as $opt) {
                $poll->options()->updateOrCreate(
                    ['id' => $opt['id']], // Find by ID
                    ['label' => $opt['label'], 'color' => $opt['color'] ?? 'gray'] // Update values
                );
            }

            session()->flash('message', 'Pillar updated successfully.');

        } else {
            // === CREATE MODE ===
            $poll = Poll::create([
                'title' => $this->title,
                'question' => $this->question,
                'description' => $this->description,
                'image_path' => $imagePath,
                'is_active' => true,
            ]);

            foreach ($this->options as $opt) {
                $poll->options()->create([
                    'label' => $opt['label'],
                    'color' => $opt['color'] ?? 'gray',
                ]);
            }

            session()->flash('message', 'Pillar created successfully.');
        }

        $this->isModalOpen = false;
        $this->loadPolls();
        $this->resetForm();
    }

    public function toggleStatus($id)
    {
        $poll = Poll::find($id);
        $poll->is_active = !$poll->is_active;
        $poll->save();
        $this->loadPolls();
    }

    public function delete($id)
    {
        Poll::find($id)->delete();
        $this->loadPolls();
    }

    public function render()
    {
        return view('livewire.director.the-pillars-manager');
    }
}