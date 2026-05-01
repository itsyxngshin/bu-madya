<?php

namespace App\Livewire\Community;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostEditor extends Component
{
    use WithFileUploads;

    public $postRecord = null;

    // Form Fields
    public $title;
    public $category_id = '';
    public $content;
    public $is_published = true;

    // Media
    public $cover_image;
    public $existing_cover;

    public $gallery_uploads = [];
    public $existing_gallery = [];

    public function mount(Post $post = null)
    {
        // Abort if not logged in
        if (!auth()->check()) {
            abort(403, 'You must be logged in to create a post.');
        }

        if ($post && $post->exists) {
            // Security: Only the author (or an admin) can edit this post
            if (auth()->id() !== $post->user_id && !auth()->user()->isAdmin) {
                abort(403, 'Unauthorized action. You can only edit your own posts.');
            }

            $this->postRecord = $post;
            $this->title = $post->title;
            $this->category_id = $post->category_id;
            $this->content = $post->content;
            $this->is_published = $post->is_published;
            $this->existing_cover = $post->cover_image_path;
            $this->existing_gallery = $post->gallery ?? [];
        }
    }

    private function scanForViolations($text)
    {
        // Your community guidelines blocklist
        // You can expand this or eventually move it to a database table
        $restrictedWords = [
            'spam link', 'hate speech', 'profanity1', 'profanity2' 
        ];

        $caughtWords = [];
        $lowerText = strtolower($text);

        foreach ($restrictedWords as $word) {
            // Using \b ensures we match whole words only (e.g., 'ass' won't flag 'class')
            if (preg_match("/\b" . preg_quote($word, '/') . "\b/i", $lowerText)) {
                $caughtWords[] = $word;
            }
        }

        return $caughtWords;
    }

    public function removeNewGalleryItem($index)
    {
        array_splice($this->gallery_uploads, $index, 1);
    }

    public function removeExistingGalleryItem($index)
    {
        // Delete the actual file from storage
        if (isset($this->existing_gallery[$index])) {
            Storage::disk('public')->delete($this->existing_gallery[$index]);
            array_splice($this->existing_gallery, $index, 1);
        }
    }

    public function removeCover()
    {
        if ($this->existing_cover) {
            Storage::disk('public')->delete($this->existing_cover);
            $this->existing_cover = null;
        }
        $this->cover_image = null;
    }

    public function savePost()
    {
        $totalPhotos = count($this->existing_gallery) + count($this->gallery_uploads);

        $this->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required|string|min:20',
            'cover_image' => 'nullable|image|max:3072', // 3MB max
            'gallery_uploads' => 'nullable|array',
            'gallery_uploads.*' => 'image|max:3072',
        ]);

        if ($totalPhotos > 4) {
            $this->addError('gallery_uploads', 'You can only attach a maximum of 4 photos total.');
            return;
        }

        // Process Cover Image
        $coverPath = $this->existing_cover;
        if ($this->cover_image) {
            if ($this->existing_cover) { Storage::disk('public')->delete($this->existing_cover); }
            $coverPath = $this->cover_image->store('posts/covers', 'public');
        }

        // Process Gallery Images
        $galleryPaths = $this->existing_gallery;
        if (!empty($this->gallery_uploads)) {
            foreach ($this->gallery_uploads as $photo) {
                $galleryPaths[] = $photo->store('posts/gallery', 'public');
            }
        }

        // 1. Scan the title and content combined
        $textToScan = $this->title . ' ' . $this->content;
        $violations = $this->scanForViolations($textToScan);
        
        $isFlagged = count($violations) > 0;
        
        // 2. If flagged, force it to be hidden, regardless of user's choice
        $finalPublishedState = $isFlagged ? false : $this->is_published;

        $data = [
            'user_id' => auth()->id(),
            'category_id' => $this->category_id ?: null,
            'title' => $this->title,
            'excerpt' => Str::limit(strip_tags($this->content), 150),
            'content' => $this->content,
            'cover_image_path' => $coverPath,
            'gallery' => $galleryPaths,
            'is_published' => $finalPublishedState,
            'is_flagged' => $isFlagged,
            'flagged_words' => $isFlagged ? implode(', ', $violations) : null,
        ];


        if ($this->postRecord) {
            // Update
            $this->postRecord->update($data);
            $post = $this->postRecord;
            $message = 'Post updated successfully!';
        } else {
            // Create
            $data['slug'] = Str::slug($this->title . '-' . uniqid());
            $data['published_at'] = $this->is_published ? now() : null;
            $post = Post::create($data);
            $message = 'Post published successfully!';
        }

        if ($isFlagged) {
            session()->flash('error', 'Your post contains restricted words and has been flagged for admin review. It will not be published until approved.');
        } else {
            session()->flash('success', 'Post saved successfully!');
        }
        // Redirect back to the feed (or the post itself)
        return redirect()->route('community.feed');
    }

    public function render()
    {
        return view('livewire.community.post-editor', [
            'categories' => Category::orderBy('name')->get()
        ])->layout('layouts.madya-community');
    }
}
