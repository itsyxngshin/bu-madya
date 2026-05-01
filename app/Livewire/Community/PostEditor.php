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

        $data = [
            'user_id' => auth()->id(),
            'category_id' => $this->category_id ?: null,
            'title' => $this->title,
            'excerpt' => Str::limit(strip_tags($this->content), 150),
            'content' => $this->content,
            'cover_image_path' => $coverPath,
            'gallery' => $galleryPaths,
            'is_published' => $this->is_published,
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

        session()->flash('success', $message);

        // Redirect back to the feed (or the post itself)
        return redirect()->route('community.feed');
    }

    public function render()
    {
        return view('livewire.community.post-editor', [
            'categories' => Category::orderBy('name')->get()
        ])->layout('layouts.madya-template');
    }
}
