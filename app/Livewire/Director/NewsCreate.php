<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class NewsCreate extends Component
{
    use WithFileUploads;

    // Default Data
    public $title = 'New Advocacy Initiative';
    public $category = 'Advocacy';
    public $author = 'Secretariat Committee';
    public $date;
    public $content = "Start writing your story here...";
    public $imageUrl = 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070';
    public $tags = 'Youth, Leadership';

    public function mount()
    {
        $this->date = now()->format('F d, Y');
    }

    public function saveDraft()
    {
        // Backend logic would go here
        session()->flash('message', 'Draft saved successfully.');
    }

    public function publish()
    {
        // Backend logic would go here
        session()->flash('message', 'Article published!');
    }

    public function render()
    {
        return view('livewire.director.news-create');
    }
}
