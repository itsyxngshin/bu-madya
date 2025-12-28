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

#[Layout('layouts.madya-template')]
class NewsEdit extends Component
{
    use WithFileUploads;

    public $article;

    // Form Properties
    public $title;
    public $slug; 
    public $category;
    public $content;
    public $tags;
    
    // Authors (Arrays for logic loop)
    public $authors = [];
    public $authorMatches = []; // Search results

    // Date
    public $published_at;

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
        // 1. Load Article with Relationships
        $this->article = News::with(['category', 'sdgs', 'authors'])->where('slug', $slug)->firstOrFail();

        if (auth()->id() !== $this->article->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Fill Basic Data
        $this->title = $this->article->title;
        $this->slug = $this->article->slug;
        $this->category = $this->article->category->name ?? '';
        $this->content = $this->article->content;
        $this->tags = $this->article->tags;
        $this->imageUrl = $this->article->cover_img;
        $this->summary = $this->article->summary;
        $this->photo_credit = $this->article->photo_credit;
        $this->show_drop_cap = (bool) $this->article->show_drop_cap;
        
        // 3. Fill Date
        $this->published_at = $this->article->published_at?->format('Y-m-d');

        // 4. Fill Authors (Pivot to Array)
        $this->authors = $this->article->authors->map(function($author) {
            return [
                'name' => $author->name,
                'type' => $author->type,
                'user_id' => $author->user_id
            ];
        })->toArray();

        // Fallback for legacy data (if pivot is empty)
        if (empty($this->authors)) {
            $this->authors = [[
                'name' => $this->article->author ?? 'Secretariat', 
                'type' => 'Head Writer', 
                'user_id' => $this->article->user_id
            ]];
        }

        // 5. Fill SDGs
        $this->selectedSdgs = $this->article->sdgs->pluck('id')->toArray();
    }

    // --- Author Search & Management ---

    public function addAuthor()
    {
        $this->authors[] = ['name' => '', 'type' => 'Contributor', 'user_id' => null];
    }

    public function removeAuthor($index)
    {
        if (count($this->authors) > 1) {
            unset($this->authors[$index]);
            $this->authors = array_values($this->authors);
            if (isset($this->authorMatches[$index])) {
                unset($this->authorMatches[$index]);
            }
        }
    }

    // Real-time Search Hook
    public function updatedAuthors($value, $key)
    {
        $parts = explode('.', $key);
        
        if (count($parts) === 2 && $parts[1] === 'name') {
            $index = $parts[0];
            $this->authors[$index]['user_id'] = null; // Reset ID on type

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

    public function selectUser($index, $userId, $userName)
    {
        $this->authors[$index]['name'] = $userName;
        $this->authors[$index]['user_id'] = $userId;
        $this->authorMatches[$index] = [];
    }

    // --- Visuals ---

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

    // --- Save Logic ---

    public function saveDraft()
    {
        $this->commitSave('draft');
    }

    public function publish()
    {
        $this->commitSave('active');
    }

    private function commitSave($targetStatus)
    {
        $this->validate([
            'title' => 'required|min:5',
            'slug' => 'required|alpha_dash|unique:news,slug,' . $this->article->id,
            'category' => 'required',
            'content' => 'required',
            'authors.*.name' => 'required',
            'published_at' => 'nullable|date',
        ]);

        $cat = NewsCategory::firstOrCreate(['name' => $this->category]);

        // Update Main Article
        $this->article->update([
            'news_category_id' => $cat->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'author' => $this->authors[0]['name'] ?? 'Unknown', // Legacy fallback
            'content' => $this->content,
            'cover_img' => $this->imageUrl,
            'tags' => $this->tags,
            'summary' => $this->summary,
            'photo_credit' => $this->photo_credit,
            'show_drop_cap' => $this->show_drop_cap,
            'status' => $targetStatus,
            'published_at' => $this->published_at,
        ]);

        // Sync Authors (Delete Old -> Create New)
        NewsAuthor::where('news_id', $this->article->id)->delete();

        foreach ($this->authors as $authData) {
            NewsAuthor::create([
                'news_id' => $this->article->id,
                'user_id' => $authData['user_id'] ?? null,
                'name' => $authData['name'],
                'type' => $authData['type'],
            ]);
        }

        // Sync SDGs
        $this->article->sdgs()->sync($this->selectedSdgs);

        session()->flash('message', $targetStatus === 'active' ? 'Article Published!' : 'Draft Saved!');
        
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