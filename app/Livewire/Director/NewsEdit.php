<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class NewsEdit extends Component
{
    use WithFileUploads;

    public $articleId;

    // Form Fields
    public $title;
    public $category;
    public $author;
    public $date;
    public $content;
    public $imageUrl;
    public $tags;

    public function mount($id)
    {
        $this->articleId = $id;

        // SIMULATED DB FETCH
        // In real app: $article = News::findOrFail($id);
        $article = [
            'title' => 'BU MADYA Launches National Youth Advocacy Summit 2025',
            'category' => 'Advocacy',
            'author' => 'Secretariat Committee',
            'date' => 'December 12, 2024',
            'content' => "The youth are not just the leaders of tomorrow, but the partners of today...\n\n### A Vision for 2030\n\nDuis aute irure dolor in reprehenderit...",
            'img' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070',
            'tags' => 'Youth, Leadership, SDGs'
        ];

        // Fill properties
        $this->title = $article['title'];
        $this->category = $article['category'];
        $this->author = $article['author'];
        $this->date = $article['date'];
        $this->content = $article['content'];
        $this->imageUrl = $article['img'];
        $this->tags = $article['tags'];
    }

    public function update()
    {
        // DB Update Logic: News::find($this->articleId)->update(...)
        
        session()->flash('message', 'Article updated successfully!');
    }

    public function render()
    {
        return view('livewire.director.news-edit');
    }
}
