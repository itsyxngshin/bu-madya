<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RoundtableTopic;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class RoundtableIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isCreateModalOpen = false;
    
    // Create Form
    public $headline = '';
    public $content = '';

    public function render()
    {
        $topics = RoundtableTopic::with('user')
            ->withCount('roundtable_replies')
            ->when($this->search, fn($q) => $q->where('headline', 'like', '%'.$this->search.'%'))
            ->orderBy('is_pinned', 'desc') // Pinned first
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.open.roundtable-index', ['topics' => $topics]); // Assuming authenticated layout
    }

    public function createTopic()
    {
        $this->validate([
            'headline' => 'required|min:5|max:100',
            'content' => 'required|min:10',
        ]);

        RoundtableTopic::create([
            'user_id' => auth()->id(),
            'headline' => $this->headline,
            'content' => $this->content,
            'slug' => Str::slug($this->headline) . '-' . uniqid(),
        ]);

        $this->reset(['headline', 'content', 'isCreateModalOpen']);
        session()->flash('message', 'Topic started successfully.');
    }
}