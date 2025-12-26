<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Sdg;
use Illuminate\Support\Str;

#[Layout('layouts.madya-template')]
class NewsEdit extends Component
{
    use WithFileUploads;

    public $article;

    // Form Properties
    public $title;
    public $slug; // <--- Fully Editable
    public $category;
    public $author;
    public $content;
    public $tags;
    
    // Metadata & Visuals
    public $summary;
    public $photo_credit;
    public $imageUrl;      
    public $cover_photo;   
    public $show_drop_cap = false;

    // Logic
    public $selectedSdgs = [];
    public $photo_upload; 

    public function mount($slug)
    {
        $this->article = News::with(['category', 'sdgs'])->where('slug', $slug)->firstOrFail();

        if (auth()->id() !== $this->article->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Fill data
        $this->title = $this->article->title;
        $this->slug = $this->article->slug;
        $this->category = $this->article->category->name ?? '';
        $this->author = $this->article->author;
        $this->content = $this->article->content;
        $this->tags = $this->article->tags;
        $this->imageUrl = $this->article->cover_img;
        $this->summary = $this->article->summary;
        $this->photo_credit = $this->article->photo_credit;
        $this->show_drop_cap = (bool) $this->article->show_drop_cap;
        $this->selectedSdgs = $this->article->sdgs->pluck('id')->toArray();
    }

    // 1. Hook: Only auto-update slug if the user hasn't saved the article yet 
    // or if you want to allow manual overrides, remove this method entirely.
    // For now, I'll remove updatedTitle so we don't overwrite manual slug edits.

    // 2. Image Handlers
    public function updatedCoverPhoto()
    {
        $this->validate(['cover_photo' => 'image|max:10240']);
        $this->imageUrl = $this->cover_photo->store('news-covers', 'public');
    }

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:10240']);
        $path = $this->photo_upload->store('news-content', 'public');
        $this->dispatch('photo-inserted', url: asset('storage/' . $path));
    }

    // 3. ACTION BUTTONS
    public function saveDraft()
    {
        $this->commitSave('draft');
    }

    public function publish()
    {
        $this->commitSave('active');
    }

    // 4. SHARED SAVE LOGIC
    private function commitSave($targetStatus)
    {
        // Validation
        $this->validate([
            'title' => 'required|min:5',
            // Critical: Unique check must ignore THIS article's ID
            'slug' => 'required|alpha_dash|unique:news,slug,' . $this->article->id,
            'category' => 'required',
            'content' => 'required',
            'author' => 'required',
        ]);

        $cat = NewsCategory::firstOrCreate(['name' => $this->category]);

        // Logic for Published Date:
        // If switching to Active and it wasn't active before, set date to Now.
        // If already active, keep the original date.
        $publishedDate = $this->article->published_at;
        if ($targetStatus === 'active' && $this->article->status !== 'active') {
            $publishedDate = now();
        }

        $this->article->update([
            'news_category_id' => $cat->id,
            'title' => $this->title,
            'slug' => $this->slug, // Saves the edited slug
            'author' => $this->author,
            'content' => $this->content,
            'cover_img' => $this->imageUrl,
            'tags' => $this->tags,
            'summary' => $this->summary,
            'photo_credit' => $this->photo_credit,
            'show_drop_cap' => $this->show_drop_cap,
            'status' => $targetStatus, // Updates status (Draft/Active)
            'published_at' => $publishedDate,
        ]);

        $this->article->sdgs()->sync($this->selectedSdgs);

        session()->flash('message', $targetStatus === 'active' ? 'Article Published!' : 'Draft Saved!');
        
        // Redirect using the (potentially new) slug
        return redirect()->route('news.show', $this->slug);
    }

    public function render()
    {
        return view('livewire.director.news-edit', [
            'sdgOptions' => Sdg::all(),
            'categoryOptions' => NewsCategory::all(),
        ]);
    }
}