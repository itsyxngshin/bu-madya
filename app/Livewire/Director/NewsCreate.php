<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsAuthor;
use App\Models\Sdg;
use App\Models\User; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.madya-template')]
class NewsCreate extends Component
{
    use WithFileUploads;

    // 1. Form Properties
    public $title;
    public $slug;
    public $category = '';
    public $content = "";
    public $tags;
    
    // CHANGED: Author System
    public $authors = [
        ['name' => 'Secretariat Committee', 'type' => 'Head Writer', 'user_id' => null]
    ];
    public $authorMatches = []; // Stores search results for each row

    // CHANGED: Date System
    public $published_at;

    // 2. Visuals & Metadata
    public $imageUrl;      
    public $cover_photo;   
    public $summary;
    public $photo_credit;
    public $show_drop_cap = false;

    // 3. SDG & Editor Logic
    public $selectedSdgs = []; 
    public $photo_upload;  

    public function mount()
    {
        // Default published date to today
        $this->published_at = now()->format('Y-m-d');
    }

    // --- Author Management & Search ---

    public function addAuthor()
    {
        $this->authors[] = ['name' => '', 'type' => 'Contributor', 'user_id' => null];
    }

    public function removeAuthor($index)
    {
        if (count($this->authors) > 1) {
            unset($this->authors[$index]);
            $this->authors = array_values($this->authors); // Re-index array
            
            // Clean up matches to prevent ghost dropdowns
            if (isset($this->authorMatches[$index])) {
                unset($this->authorMatches[$index]);
            }
        }
    }

    // Real-time User Search Hook
    public function updatedAuthors($value, $key)
    {
        // $key format: "0.name", "1.name"
        $parts = explode('.', $key);
        
        // Only search if the 'name' field is being typed in
        if (count($parts) === 2 && $parts[1] === 'name') {
            $index = $parts[0];
            
            // If typing, unlink previous user_id (assume manual entry until selected)
            $this->authors[$index]['user_id'] = null;

            if (strlen($value) >= 2) {
                $this->authorMatches[$index] = User::where('name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->limit(5)
                    ->get()
                    ->toArray();
            } else {
                $this->authorMatches[$index] = [];
            }
        }
    }

    // Triggered when clicking a suggestion from the dropdown
    public function selectUser($index, $userId, $userName)
    {
        $this->authors[$index]['name'] = $userName;
        $this->authors[$index]['user_id'] = $userId;
        $this->authorMatches[$index] = []; // Clear dropdown
    }

    // --- Standard Form Logic ---

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function updatedCoverPhoto()
    {
        $this->validate(['cover_photo' => 'image|max:10240']);
        $path = $this->cover_photo->store('news-covers', 'public');
        $this->imageUrl = $path;
    }

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:10240']);
        $path = $this->photo_upload->store('news-content', 'public');
        $this->dispatch('photo-inserted', url: asset('storage/' . $path));
    }

    // --- Save Actions ---

    public function saveDraft()
    {
        $this->store('draft');
    }

    public function publish()
    {
        $this->store('for evaluation');
    }

    private function store($status)
    {
        $this->validate([
            'title' => 'required|min:5',
            'slug' => 'required|alpha_dash|unique:news,slug',
            'category' => 'required',
            'content' => 'required',
            'authors.*.name' => 'required', // Validate names inside array
            'published_at' => 'nullable|date',
        ]);

        $cat = NewsCategory::firstOrCreate(['name' => $this->category]);

        // Create the Article
        $news = News::create([
            'user_id' => Auth::id(),
            'news_category_id' => $cat->id,
            'title' => $this->title,
            'slug' => $this->slug,
            // Legacy Support: Save the first author's name to the main table string
            'author' => $this->authors[0]['name'] ?? 'Unknown', 
            'content' => $this->content,
            'cover_img' => $this->imageUrl,
            'tags' => $this->tags,
            'summary' => $this->summary,
            'photo_credit' => $this->photo_credit,
            'show_drop_cap' => $this->show_drop_cap,
            'status' => $status,
            // Use the manual date picker value
            'published_at' => $this->published_at, 
        ]);

        // Sync Authors (Pivot Table)
        foreach ($this->authors as $authData) {
            NewsAuthor::create([
                'news_id' => $news->id,
                'user_id' => $authData['user_id'] ?? null, 
                'name' => $authData['name'],
                'type' => $authData['type'],
            ]);
        }

        // Sync SDGs
        if (!empty($this->selectedSdgs)) {
            $news->sdgs()->sync($this->selectedSdgs);
        }

        session()->flash('message', 'Article ' . ($status === 'active' ? 'Published' : 'Saved') . '!');
        return redirect()->route('news.index');
    }

    public function render()
    {
        return view('livewire.director.news-create', [
            'sdgOptions' => Sdg::all(),
            'categoryOptions' => NewsCategory::all()
        ]);
    }
} 