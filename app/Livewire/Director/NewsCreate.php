<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Sdg; // Ensure you have this model
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.madya-template')]
class NewsCreate extends Component
{
    use WithFileUploads;

    // 1. Form Properties
    public $title;
    public $slug;          // <--- Custom Slug
    public $category = ''; // Default empty to force selection
    public $author = 'Secretariat Committee';
    public $content = "";
    public $tags;
    
    // 2. Visuals & Metadata
    public $imageUrl;      // Stores the final path/URL for the DB
    public $cover_photo;   // Temporary variable for the file input
    public $summary;
    public $photo_credit;
    public $show_drop_cap = false;

    // 3. SDG & Editor Logic
    public $selectedSdgs = []; 
    public $photo_upload;  // For the Markdown editor upload button

    // 4. Lifecycle Hook: Auto-generate Slug
    public function updatedTitle($value)
    {
        // Only auto-update slug if user hasn't manually set a complex one yet.
        // This simple version just updates it whenever the title changes.
        $this->slug = Str::slug($value);
    }

    // 5. Image Handlers
    public function updatedCoverPhoto()
    {
        $this->validate(['cover_photo' => 'image|max:10240']); // 10MB Max
        
        // Store in 'public/news-covers'
        $path = $this->cover_photo->store('news-covers', 'public');
        
        // Set the imageUrl property so it saves to DB later
        $this->imageUrl = $path;
    }

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:10240']);

        // Store in 'public/news-content'
        $path = $this->photo_upload->store('news-content', 'public');
        
        // Send the URL back to the editor (frontend)
        $this->dispatch('photo-inserted', url: asset('storage/' . $path));
    }

    // 6. Save Actions
    public function saveDraft()
    {
        $this->store('draft');
    }

    public function publish()
    {
        $this->store('active');
    }

    private function store($status)
    {
        // Validation
        $this->validate([
            'title' => 'required|min:5',
            'slug' => 'required|alpha_dash|unique:news,slug', // Ensure unique URL
            'category' => 'required',
            'content' => 'required',
            'author' => 'required',
        ]);

        // Find or Create Category
        $cat = NewsCategory::firstOrCreate(['name' => $this->category]);

        // Create the Article
        $news = News::create([
            'user_id' => Auth::id(),
            'news_category_id' => $cat->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'author' => $this->author,
            'content' => $this->content,
            'cover_img' => $this->imageUrl, // Saves the path from updatedCoverPhoto
            'tags' => $this->tags,
            'summary' => $this->summary,
            'photo_credit' => $this->photo_credit,
            'show_drop_cap' => $this->show_drop_cap,
            'status' => $status,
            'published_at' => $status === 'active' ? now() : null,
        ]);

        // Sync SDGs (Pivot Table)
        if (!empty($this->selectedSdgs)) {
            $news->sdgs()->sync($this->selectedSdgs);
        }

        // Redirect
        session()->flash('message', 'Article ' . ($status === 'active' ? 'Published' : 'Saved') . '!');
        return redirect()->route('news.index');
    }

    public function render()
    {
        return view('livewire.director.news-create', [
            'sdgOptions' => Sdg::all(),              // Fetch all SDGs from DB
            'categoryOptions' => NewsCategory::all() // Fetch all Categories from DB
        ]);
    }
}