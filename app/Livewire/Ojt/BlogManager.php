<?php

namespace App\Livewire\Ojt;

use Livewire\Component;
use Livewire\WithFileUploads; // Add this!
use App\Models\OjtBlog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BlogManager extends Component
{
    use WithFileUploads; // Enable file uploads

    public $title = '';
    public $content = '';
    public $type = 'daily_report';
    public $reportDate;
    public $photo; // New property for the image

    public $showModal = false;

    public function mount()
    {
        $this->reportDate = Carbon::today()->format('Y-m-d');
    }

    public function saveBlog()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:daily_report,weekly_summary',
            'reportDate' => 'required|date',
            'photo' => 'nullable|image|max:5120', // Validate: Must be an image, max 5MB
        ]);

        $path = null;
        if ($this->photo) {
            // Store the file in storage/app/public/ojt-photos
            $path = $this->photo->store('ojt-photos', 'public');
        }

        OjtBlog::create([
            'user_id' => Auth::id(),
            'type' => $this->type,
            'report_date' => $this->reportDate,
            'title' => $this->title,
            'content' => $this->content,
            'attachment_path' => $path, // Save the path
        ]);

        $this->reset(['title', 'content', 'photo', 'showModal']);
        session()->flash('success', 'OJT Log saved successfully.');
    }

    public function render()
    {
        $recentBlogs = OjtBlog::where('user_id', Auth::id())
            ->orderBy('report_date', 'desc')
            ->take(5)
            ->get();

        return view('livewire.ojt.blog-manager', ['recentBlogs' => $recentBlogs]);
    }
}
