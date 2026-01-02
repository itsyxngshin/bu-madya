<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TransparencyDocument;
use App\Models\TransparencyCategory;
use App\Models\SiteStat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TransparencyIndex extends Component
{
    use WithPagination;

    // Filter States
    public $filter_category = 'all'; // Stores Category ID
    public $filter_year = 'all';     // Stores Year String
    public $search = '';
    public $visitorCount = 1;
    public function mount()
    {
        // 1. Check if this specific user has already been counted in this session
        if (!Session::has('has_visited_site')) {
            
            // 2. Increment the database value securely
            SiteStat::where('key', 'visitor_count')->increment('value');
            
            // 3. Mark this user as counted for this browser session
            Session::put('has_visited_site', true);
        }

        // 4. Retrieve the current total (cache it briefly to reduce DB queries on high traffic)
        // We remember it for 10 minutes, or fetch directly if you want instant real-time
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    // Reset pagination when filters change to avoid empty pages
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCategory() { $this->resetPage(); }
    public function updatedFilterYear() { $this->resetPage(); }

    public function setCategory($id)
    {
        $this->filter_category = $id;
        $this->resetPage();
    }

    public function download($id)
    {
        $doc = TransparencyDocument::findOrFail($id);
        $user = auth()->user();

        // 1. SECURITY CHECK
        if ($doc->visibility === 'admin') {
            if (!$user || $user->role->role_name !== 'administrator') {
                $this->dispatch('notify', message: 'Unauthorized access.', type: 'error');
                return;
            }
        } elseif ($doc->visibility === 'auth') {
            if (!$user) {
                // Redirect to login if they aren't logged in
                return redirect()->route('login');
            }
        }

        // 2. INCREMENT STATS
        $doc->increment('downloads_count');

        // 3. DOWNLOAD
        return Storage::disk('public')->download(
            $doc->file_path,
            $doc->title . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION)
        );
    }

    public function render()
    {
        $query = TransparencyDocument::with('category')
            ->orderBy('published_date', 'desc');

        // --- ACCESS CONTROL LOGIC ---
        if (auth()->check()) {
            // User is logged in
            if (in_array(Auth::user()?->role?->role_name, ['administrator', 'director'])) {
                // Admins and directors see EVERYTHING (No filter needed)
            } 
            else {
                // Regular Members see 'public' AND 'auth'
                $query->whereIn('visibility', ['public', 'auth']);
            }
        } 
        elseif (auth()->guest()) {
            // Guests see ONLY 'public'
            $query->where('visibility', 'public');
        }
        else {
            // Regular Members see 'public' AND 'auth'
            $query->whereIn('visibility', ['public', 'auth']);
        }
            // --- END ACCESS CONTROL LOGIC ---

        $query = TransparencyDocument::with('category') // Eager load for performance
            ->orderBy('published_date', 'desc');

        // 1. Filter by Category ID
        if ($this->filter_category !== 'all') {
            $query->where('category_id', $this->filter_category);
        }

        // 2. Filter by Academic Year
        if ($this->filter_year !== 'all') {
            $query->where('academic_year', $this->filter_year);
        }

        // 3. Search Title
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        return view('livewire.open.transparency-index', [
            'documents' => $query->paginate(12),
            // Pass categories for the tabs
            'categories' => TransparencyCategory::orderBy('name')->get(),
            // Get unique academic years present in the DB
            'years' => TransparencyDocument::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year')
        ])->layout('layouts.madya-template', ['title' => 'Transparency Board']);
    }
}