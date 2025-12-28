<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Models
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use App\Models\College;
use App\Models\Engagement;
use App\Models\Portfolio;
use App\Models\AcademicYear;
use App\Models\Director;
use App\Models\Committee;
use App\Models\DirectorAssignment;
use App\Models\CommitteeMember;

#[Layout('layouts.madya-admin-deck')]
class UserRoster extends Component
{
    use WithPagination;
    use WithFileUploads;

    // -- Search & Filters --
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    // -- Modal States --
    public $isCreateModalOpen = false;
    public $isAssignmentModalOpen = false;
    public $isProfileModalOpen = false;

    // -- User Data --
    public $createMode = 'single'; 
    public $newUser = ['name' => '', 'email' => '', 'password' => '', 'role_id' => ''];
    public $bulkEmails = ''; 
    public $viewingUser;

    // -- Assignment Management --
    public $selectedUserId;
    public $editingAssignmentId = null; // If set, we are editing, not creating
    public $assignYearId;
    public $assignType = 'committee';
    public $assignDirectorId;
    public $assignCommitteeId;
    public $assignTitle;
    public $editingRoleId;

    // -- Profile Editing Data --
    public $editingProfile = [
        'first_name' => '', 'last_name' => '', 'middle_name' => '',
        'college_id' => '', 'course' => '', 'year_level' => '', 'bio' => ''
    ];

    // -- Portfolio & Engagement Data --
    public $newPortfolio = [
        'designation' => '', 'place' => '', 'description' => '', 'duration' => '', 'status' => 'Active'
    ];
    public $newEngagement = [
        'title' => '', 'description' => ''
    ];

    public function mount()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $this->assignYearId = $activeYear ? $activeYear->id : null;
    }

    public function render()
    {
        $users = User::query()
            ->with(['role', 'profile', 'directorAssignment', 'committeeMember']) 
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->roleFilter, fn($q) => $q->where('role_id', $this->roleFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-roster', [
            'users' => $users,
            'roles' => Role::all(),
            'years' => AcademicYear::orderBy('name', 'desc')->get(),
            'directors' => Director::orderBy('order')->get(),
            'committees' => Committee::orderBy('name')->get(),
            'colleges'   => College::orderBy('name')->get(),
        ]);
    }

    // ... [saveUser method remains same as before] ...
    public function saveUser() { /* ... see previous response ... */ }

    // =========================================================
    // ASSIGNMENT LOGIC (Updated for Multiple/Simultaneous)
    // =========================================================

    public function openAssignmentModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->editingAssignmentId = null; // Reset edit mode
        $this->reset(['assignDirectorId', 'assignCommitteeId', 'assignTitle']);
        // Keep current year selected or default
        $this->isAssignmentModalOpen = true;
    }

    public function editAssignment($type, $id)
    {
        $this->editingAssignmentId = $id;
        $this->assignType = $type;
        $this->isAssignmentModalOpen = true;
        // Close profile to focus on edit, or keep it open behind (requires modal nesting support)
        // For simplicity, we'll close profile temporarily or just layer on top
        // $this->isProfileModalOpen = false; 

        if ($type === 'director') {
            $record = DirectorAssignment::find($id);
            $this->selectedUserId = $record->user_id;
            $this->assignYearId = $record->academic_year_id;
            $this->assignDirectorId = $record->director_id;
            $this->assignCommitteeId = $record->committee_id;
        } else {
            $record = CommitteeMember::find($id);
            $this->selectedUserId = $record->user_id;
            $this->assignYearId = $record->academic_year_id;
            $this->assignCommitteeId = $record->committee_id;
            $this->assignTitle = $record->title;
        }
    }

    public function saveAssignment()
    {
        $this->validate([
            'assignYearId' => 'required|exists:academic_years,id',
            'assignType' => 'required|in:director,committee',
        ]);

        // NOTE: We REMOVED the delete() logic here to allow simultaneous roles

        if ($this->assignType === 'director') {
            $this->validate(['assignDirectorId' => 'required|exists:directors,id']);
            
            $data = [
                'user_id' => $this->selectedUserId,
                'academic_year_id' => $this->assignYearId,
                'director_id' => $this->assignDirectorId,
                'committee_id' => $this->assignCommitteeId,
                'title' => Director::find($this->assignDirectorId)->name, 
            ];

            if ($this->editingAssignmentId) {
                DirectorAssignment::find($this->editingAssignmentId)->update($data);
            } else {
                DirectorAssignment::create($data);
            }

        } else {
            $this->validate([
                'assignCommitteeId' => 'required|exists:committees,id',
                'assignTitle' => 'required|string',
            ]);

            $data = [
                'user_id' => $this->selectedUserId,
                'academic_year_id' => $this->assignYearId,
                'committee_id' => $this->assignCommitteeId,
                'title' => $this->assignTitle,
            ];

            if ($this->editingAssignmentId) {
                CommitteeMember::find($this->editingAssignmentId)->update($data);
            } else {
                CommitteeMember::create($data);
            }
        }

        session()->flash('message', 'Assignment saved successfully.');
        $this->isAssignmentModalOpen = false;
        
        // Refresh profile view if it was open
        if ($this->viewingUser && $this->viewingUser->id == $this->selectedUserId) {
            $this->viewProfile($this->selectedUserId);
        }
    }

    public function deleteAssignment($type, $id)
    {
        if ($type === 'director') {
            DirectorAssignment::find($id)->delete();
        } else {
            CommitteeMember::find($id)->delete();
        }
        
        session()->flash('message', 'Role removed.');
        $this->viewProfile($this->viewingUser->id); // Refresh
    }

    // =========================================================
    // PROFILE, PORTFOLIO & ENGAGEMENT
    // =========================================================

    public function viewProfile($id)
    {
        $this->viewingUser = User::with([
            'role',
            'profile.college',
            'profile.portfolios', 
            'engagements',
            'directorAssignments.academicYear', 'directorAssignments.director', 
            'committeeMembers.academicYear', 'committeeMembers.committee'
        ])->find($id);
        
        
        $p = $this->viewingUser->profile;
        if ($p) {
            $this->editingProfile = [
                'first_name' => $p->first_name, 'last_name' => $p->last_name, 'middle_name' => $p->middle_name,
                'college_id' => $p->college_id, 'course' => $p->course, 'year_level' => $p->year_level, 'bio' => $p->bio,
            ];
        } else {
            // Handle missing profile case safely
             $this->editingProfile = ['first_name' => '', 'last_name' => '', 'middle_name' => '', 'college_id' => '', 'course' => '', 'year_level' => '', 'bio' => ''];
        }
        $this->editingRoleId = $this->viewingUser->role_id;
        $this->isProfileModalOpen = true;
    }

    public function updateProfile()
    {
        $this->validate([
            'editingProfile.first_name' => 'required|string',
            'editingProfile.last_name' => 'required|string',
            'editingRoleId' => 'required|exists:roles,id', // ADD VALIDATION
        ]);

        $profile = $this->viewingUser->profile;
        
        // Update Profile Table
        $this->viewingUser->profile()->updateOrCreate(
            ['user_id' => $this->viewingUser->id], 
            $this->editingProfile
        );

        // Update User Table (Name AND Role)
        $this->viewingUser->update([
            'name' => trim($this->editingProfile['first_name'] . ' ' . $this->editingProfile['last_name']),
            'role_id' => $this->editingRoleId // <--- SAVE THE NEW ROLE
        ]);

        session()->flash('message', 'Profile and Role updated successfully.');
        $this->viewProfile($this->viewingUser->id);
    }

    // -- ENGAGEMENTS --
    public function saveEngagement()
    {
        $this->validate(['newEngagement.title' => 'required']);
        
        Engagement::create([
            'user_id' => $this->viewingUser->id,
            'title' => $this->newEngagement['title'],
            'description' => $this->newEngagement['description']
        ]);

        $this->reset('newEngagement');
        $this->viewProfile($this->viewingUser->id); // Refresh list
    }

    public function deleteEngagement($id)
    {
        Engagement::find($id)->delete();
        $this->viewProfile($this->viewingUser->id);
    }

    // -- PORTFOLIOS --
    public function savePortfolio()
    {
        $this->validate(['newPortfolio.designation' => 'required']);

        // 1. Create Portfolio Item
        $portfolio = Portfolio::create($this->newPortfolio);

        // 2. Attach to Profile (Many-to-Many)
        // Make sure profile exists first
        if (!$this->viewingUser->profile) {
            $this->updateProfile(); // Trigger create if missing
        }
        
        $this->viewingUser->profile->portfolios()->attach($portfolio->id);

        $this->reset('newPortfolio');
        $this->viewProfile($this->viewingUser->id);
    }

    public function deletePortfolio($id)
    {
        // Detach and Delete
        $this->viewingUser->profile->portfolios()->detach($id);
        Portfolio::find($id)->delete();
        $this->viewProfile($this->viewingUser->id);
    }

    public function toggleStatus($id, $newStatus)
    {
        User::where('id', $id)->update(['status' => $newStatus]);
        session()->flash('message', "Status updated.");
    }
} 