<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\News;
use App\Models\NewsCategory;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class NewsRoster extends Component
{
    use WithPagination;

    // Filter properties
    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = ''; 

    // Reset pagination when any filter changes
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategoryFilter() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function deleteArticle($id)
    {
        $article = News::find($id);
        
        if ($article) {
            // Detach pivot relationships first (optional if cascade is set in DB, but safe to do)
            $article->authors()->detach();
            $article->sdgs()->detach();
            
            $article->delete();
            session()->flash('message', 'Article deleted successfully.');
        }
    }

    public function updateStatus($id, $newStatus)
    {
        $article = News::find($id);
        
        if ($article) {
            $article->status = $newStatus;
            
            // If approving, ensure published_at is set
            if ($newStatus === 'active' && !$article->published_at) {
                $article->published_at = now();
            }
            
            $article->save();
            session()->flash('message', 'Status updated successfully.');
        }
    }

    public function render()
    {
        $articles = News::query()
            ->with(['category', 'authors']) // Eager load for performance
            ->when($this->search, function($q) {
                $q->where(function($sub) {
                    $sub->where('title', 'like', '%' . $this->search . '%')
                        // Search the legacy string OR the related authors
                        ->orWhere('author', 'like', '%' . $this->search . '%')
                        ->orWhereHas('authors', function($authQ) {
                            $authQ->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->categoryFilter, function($q) {
                // Assuming you store the string name in 'news' table, 
                // OR you might need to filter by relationship:
                $q->whereHas('category', function($c) {
                    $c->where('name', $this->categoryFilter);
                });
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.news-roster', [
            'articles'   => $articles,
            'categories' => NewsCategory::orderBy('name')->pluck('name')
        ]);
    }
}