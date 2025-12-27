<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\Layout;

// Import all models
use App\Models\AcademicYear;
use App\Models\College;
use App\Models\Committee;
use App\Models\Director;
use App\Models\NewsCategory;
use App\Models\ProjectCategory;
use App\Models\LinkageType;
use App\Models\LinkageStatus;
use App\Models\AgreementLevel;

#[Layout('layouts.madya-admin-deck')]
class Settings extends Component
{
    use WithPagination;

    // Navigation
    public $activeTab = 'academic-years';
    public $search = '';

    // Form Fields
    public $selectedId;
    public $name, $description, $color;
    public $start_date, $end_date; // Specific to Academic Years
    
    public $isModalOpen = false;

    // Configuration Map
    protected $tabs = [
        // --- Group: Academics ---
        'academic-years'    => ['type' => 'crud', 'model' => AcademicYear::class,    'title' => 'Academic Year'],
        'colleges'          => ['type' => 'crud', 'model' => College::class,         'title' => 'College'],
        
        // --- Group: Organization ---
        'committees'        => ['type' => 'crud', 'model' => Committee::class,       'title' => 'Committee'],
        'directors'         => ['type' => 'crud', 'model' => Director::class,        'title' => 'Director Position'],
        
        // --- Group: Content ---
        'news-categories'   => ['type' => 'crud', 'model' => NewsCategory::class,    'title' => 'News Category'],
        'project-categories'=> ['type' => 'crud', 'model' => ProjectCategory::class, 'title' => 'Project Category'],
        
        // --- Group: Linkages ---
        'linkage-types'     => ['type' => 'crud', 'model' => LinkageType::class,     'title' => 'Linkage Type'],
        'linkage-statuses'  => ['type' => 'crud', 'model' => LinkageStatus::class,   'title' => 'Linkage Status'],
        'agreement-levels'  => ['type' => 'crud', 'model' => AgreementLevel::class,  'title' => 'Agreement Level'],
        
        // --- Group: System ---
        'maintenance'       => ['type' => 'system', 'title' => 'Maintenance & Security'],
        'system-logs'       => ['type' => 'system', 'title' => 'System Logs'],
    ];

    // Reset state when switching tabs
    public function updatedActiveTab() 
    { 
        $this->resetPage(); 
        $this->reset(['search', 'name', 'description', 'color', 'start_date', 'end_date', 'selectedId', 'isModalOpen']);
    }

    public function render()
    {
        $currentConfig = $this->tabs[$this->activeTab];
        $items = [];
        $logs = [];

        // 1. Logic for CRUD Tabs
        if ($currentConfig['type'] === 'crud') {
            $model = $currentConfig['model'];
            $query = $model::query();

            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }

            // Academic Years sort by name (year), others by newest
            if ($this->activeTab === 'academic-years') {
                $query->orderBy('name', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            $items = $query->paginate(10);
        }

        // 2. Logic for Log Tab
        if ($this->activeTab === 'system-logs') {
            $logPath = storage_path('logs/laravel.log');
            if (File::exists($logPath)) {
                // Read file, split by lines, reverse to see newest first, take top 50
                $fileContent = File::get($logPath);
                $logs = array_slice(array_reverse(explode("\n", $fileContent)), 0, 50);
            } else {
                $logs = ['Log file is empty or does not exist.'];
            }
        }

        return view('livewire.admin.settings', [
            'items'       => $items,
            'logs'        => $logs,
            'currentTab'  => $currentConfig,
            'tabList'     => $this->tabs,
            'isDown'      => Cache::get('app_lockdown_mode', false)
        ]);
    }

    // --- CRUD ACTIONS ---

    public function create()
    {
        $this->reset(['name', 'description', 'color', 'start_date', 'end_date', 'selectedId']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $currentConfig = $this->tabs[$this->activeTab];
        $item = $currentConfig['model']::find($id);

        $this->selectedId = $id;
        $this->name = $item->name;
        
        // Optional fields check
        $this->description = $item->description ?? null;
        $this->color = $item->color ?? null;

        // Academic Year specific fields
        if ($this->activeTab === 'academic-years') {
            $this->start_date = $item->start_date; 
            $this->end_date = $item->end_date;
        }

        $this->isModalOpen = true;
    }

    public function store()
    {
        $currentConfig = $this->tabs[$this->activeTab];
        $model = $currentConfig['model'];

        // Dynamic Validation
        $rules = ['name' => 'required|string|max:255'];
        
        if ($this->activeTab === 'academic-years') {
            $rules['start_date'] = 'required|date';
            $rules['end_date']   = 'required|date|after:start_date';
            // Ensure unique name for academic years
            $rules['name'] = 'required|string|max:255|unique:academic_years,name,' . $this->selectedId;
        }

        $this->validate($rules);

        // Prepare Payload
        $data = ['name' => $this->name];

        // Generate Slug for non-academic year models
        if ($this->activeTab !== 'academic-years') {
            $data['slug'] = Str::slug($this->name);
        }

        if ($this->description) $data['description'] = $this->description;
        if ($this->color)       $data['color']       = $this->color;
        
        if ($this->activeTab === 'academic-years') {
            $data['start_date'] = $this->start_date;
            $data['end_date']   = $this->end_date;
            if (!$this->selectedId) $data['is_active'] = false; // Default inactive
        }

        // Execute Save
        if ($this->selectedId) {
            $model::find($this->selectedId)->update($data);
            session()->flash('message', "{$currentConfig['title']} updated successfully.");
        } else {
            $model::create($data);
            session()->flash('message', "{$currentConfig['title']} created successfully.");
        }

        $this->isModalOpen = false;
        $this->reset(['name', 'description', 'color', 'start_date', 'end_date', 'selectedId']);
    }

    public function delete($id)
    {
        $currentConfig = $this->tabs[$this->activeTab];
        $item = $currentConfig['model']::find($id);

        if ($item) {
            // Guard: Prevent deleting active academic year
            if ($this->activeTab === 'academic-years' && $item->is_active) {
                session()->flash('error', 'Cannot delete the currently active academic year.');
                return;
            }

            $item->delete();
            session()->flash('message', 'Item deleted.');
        }
    }

    public function toggleActiveYear($id)
    {
        if ($this->activeTab !== 'academic-years') return;

        // Deactivate all, Activate one
        AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::where('id', $id)->update(['is_active' => true]);
        
        session()->flash('message', 'Active academic year updated.');
    }


    // --- SYSTEM ACTIONS ---

    public function toggleLockdown()
    {
        $currentStatus = Cache::get('app_lockdown_mode', false);
        
        if ($currentStatus) {
            Cache::forget('app_lockdown_mode');
            session()->flash('message', 'System is LIVE. Lockdown lifted.');
        } 
        else {
            Cache::put('app_lockdown_mode', true);
            session()->flash('error', 'SYSTEM LOCKDOWN ACTIVE. Only admins can access the system.');
        }
    }

    public function clearLogs()
    {
        File::put(storage_path('logs/laravel.log'), '');
        session()->flash('message', 'System logs cleared.');
    }
    
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        session()->flash('message', 'Application cache cleared.');
    }
}