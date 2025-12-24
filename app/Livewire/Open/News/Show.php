<?php

namespace App\Livewire\Open\News;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Show extends Component
{
    public $articleId;
    public $article;

    public function mount($id)
    {
        $this->articleId = $id;

        // Simulate fetching from DB
        $this->article = [
            'id' => $id,
            'title' => 'BU MADYA Launches National Youth Advocacy Summit 2025',
            'category' => 'Advocacy',
            'date' => 'December 12, 2024',
            'author' => 'Secretariat Committee',
            'img' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070',
            'content' => 'Lorem ipsum dolor sit amet...', // Add full content here
        ]; 
    }

    public function render()
    {
        return view('livewire.open.news.show');
    }
}
