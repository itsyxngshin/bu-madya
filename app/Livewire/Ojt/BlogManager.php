<?php

namespace App\Livewire\Ojt;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\OjtBlog;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogManager extends Component
{
    use WithFileUploads;

    // Journal Narrative Variables
    public $title = '';
    public $content = '';
    public $type = 'daily_report';
    public $reportDate;
    public $photo;
    
    // Variables for editing
    public $editingBlogId = null;
    public $existingPhotoPath = null;

    public $showModal = false;

    // Tracker Settings
    public $includeOvertimeInTotal = true; 
    public $targetHours = 486; 

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['title', 'content', 'photo', 'editingBlogId', 'existingPhotoPath']);
        $this->reportDate = Carbon::today()->format('Y-m-d');
        $this->type = 'daily_report';
        $this->showModal = false;
    }

    public function createNewEntry()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editBlog($id)
    {
        $blog = OjtBlog::where('user_id', Auth::id())->findOrFail($id);
        
        $this->editingBlogId = $blog->id;
        $this->title = $blog->title;
        $this->content = $blog->content;
        $this->type = $blog->type;
        $this->reportDate = Carbon::parse($blog->report_date)->format('Y-m-d');
        
        $this->existingPhotoPath = $blog->attachment_path;
        
        $this->showModal = true;
    }

    public function saveBlog()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:daily_report,weekly_summary',
            'reportDate' => 'required|date',
            'photo' => 'nullable|image|max:5120',
        ]);

        $photoPath = $this->existingPhotoPath;

        if ($this->photo) {
            if ($this->existingPhotoPath) Storage::disk('public')->delete($this->existingPhotoPath);
            $photoPath = $this->photo->store('ojt-photos', 'public');
        }

        $data = [
            'type' => $this->type,
            'report_date' => $this->reportDate,
            'title' => $this->title,
            'content' => $this->content,
            'attachment_path' => $photoPath,
            // OT logic completely removed from the journal!
        ];

        if ($this->editingBlogId) {
            OjtBlog::where('user_id', Auth::id())->findOrFail($this->editingBlogId)->update($data);
            session()->flash('success', 'Journal Entry updated successfully.');
        } else {
            $data['user_id'] = Auth::id();
            OjtBlog::create($data);
            session()->flash('success', 'Journal Entry saved successfully.');
        }

        $this->resetForm();
    }

    // ==========================================
    // DTR / TRACKER LOGIC (Strictly handles TimeLog)
    // ==========================================
    public function toggleDailyOvertime($logId)
    {
        // This now strictly updates the physical DTR. The journal is no longer involved.
        $timeLog = TimeLog::where('user_id', Auth::id())->findOrFail($logId);
        $timeLog->is_overtime_approved = !$timeLog->is_overtime_approved;
        $timeLog->save();
    }

    private function calculateSplitMinutes($log)
    {
        // Define the strict boundaries of the OJT shifts
        $morningStart = Carbon::parse($log->log_date)->setTime(8, 0, 0);
        $morningEnd   = Carbon::parse($log->log_date)->setTime(12, 0, 0);
        
        $afternoonStart = Carbon::parse($log->log_date)->setTime(13, 0, 0); // 1:00 PM
        $afternoonEnd   = Carbon::parse($log->log_date)->setTime(17, 0, 0); // 5:00 PM

        $calc = function($otAllowed) use ($log, $morningStart, $morningEnd, $afternoonStart, $afternoonEnd) {
            $minutes = 0;

            // 1. Process Morning Shift (Strictly clamped to 8 AM - 12 PM)
            if ($log->morning_in && $log->morning_out) {
                $mIn = Carbon::parse($log->morning_in);
                $mOut = Carbon::parse($log->morning_out);

                if ($mIn->lessThan($morningStart)) $mIn = $morningStart->copy();
                if ($mOut->greaterThan($morningEnd)) $mOut = $morningEnd->copy();

                if ($mOut->greaterThan($mIn)) {
                    $minutes += $mIn->diffInMinutes($mOut);
                }
            }

            // 2. Process Afternoon Shift (Strictly starts at 1 PM)
            if ($log->afternoon_in && $log->afternoon_out) {
                $aIn = Carbon::parse($log->afternoon_in);
                $aOut = Carbon::parse($log->afternoon_out);

                if ($aIn->lessThan($afternoonStart)) $aIn = $afternoonStart->copy();
                
                // Only clamp the 5:00 PM departure if OT is NOT allowed
                if (!$otAllowed && $aOut->greaterThan($afternoonEnd)) {
                    $aOut = $afternoonEnd->copy();
                }

                if ($aOut->greaterThan($aIn)) {
                    $minutes += $aIn->diffInMinutes($aOut);
                }
            }

            return $minutes;
        };

        $regularMins = $calc(false); 
        $actualMins = $calc($log->is_overtime_approved); 
        $potentialMins = $calc(true); // Always calculates max possible hours if OT were approved

        return [
            'regular' => $regularMins,
            'overtime' => $actualMins - $regularMins,
            'potential_overtime' => $potentialMins - $regularMins
        ];
    }

    public function render()
    {
        $timeLogs = TimeLog::where('user_id', Auth::id())->orderBy('log_date', 'asc')->get();
        $allBlogs = OjtBlog::where('user_id', Auth::id())->orderBy('report_date', 'asc')->get();
        
        // 1. Determine the absolute start of their OJT
        $earliestLogDate = $timeLogs->first()->log_date ?? null;
        $earliestBlogDate = $allBlogs->first()->report_date ?? null;
        
        $startDates = array_filter([$earliestLogDate, $earliestBlogDate]);
        
        if (empty($startDates)) {
            $earliestDate = Carbon::today()->startOfWeek();
        } else {
            $earliestDate = Carbon::parse(min($startDates))->startOfWeek();
        }

        $latestDate = Carbon::today()->startOfWeek(); // Generate weeks up to the present week

        // 2. Generate a continuous skeleton of all weeks
        $weeklyData = collect();
        $currentWeek = $earliestDate->copy();
        $weekCounter = 1;

        while ($currentWeek->lte($latestDate)) {
            $weekKey = $currentWeek->format('Y-m-d');
            $weeklyData->put($weekKey, [
                'week_number' => $weekCounter,
                'label' => 'Week of ' . $currentWeek->format('M d, Y'),
                'sunday_date' => $currentWeek->copy()->endOfWeek(),
                'logs' => collect(),
                'blogs' => collect()
            ]);
            $currentWeek->addWeek();
            $weekCounter++;
        }

        // 3. Process and slot DTR Time Logs
        $totalRegularMins = 0;
        $totalOvertimeMins = 0;

        foreach ($timeLogs as $log) {
            $split = $this->calculateSplitMinutes($log);
            
            $regHrs = round($split['regular'] / 60, 2);
            $otHrs = round($split['overtime'] / 60, 2);
            
            $totalRegularMins += $split['regular'];
            $totalOvertimeMins += $split['overtime'];

            $weekKey = Carbon::parse($log->log_date)->startOfWeek()->format('Y-m-d');

            if ($weeklyData->has($weekKey)) {
                $weeklyData[$weekKey]['logs']->push((object)[
                    'id' => $log->id,
                    'date' => Carbon::parse($log->log_date),
                    'regular_hrs' => $regHrs,
                    'overtime_hrs' => $otHrs,
                    'potential_overtime_hrs' => round($split['potential_overtime'] / 60, 2),
                    'is_overtime_approved' => $log->is_overtime_approved,
                    'total_hrs' => $regHrs + $otHrs
                ]);
            }
        }

        // 4. Process and slot Journal Entries
        foreach ($allBlogs as $blog) {
            $weekKey = Carbon::parse($blog->report_date)->startOfWeek()->format('Y-m-d');
            if ($weeklyData->has($weekKey)) {
                // Reverse sorting the blogs inside the week so newest is on top
                $weeklyData[$weekKey]['blogs']->prepend($blog); 
            }
        }

        // 5. Reverse the weeks so the current week is at the top of the feed
        $weeklyData = $weeklyData->sortKeysDesc();

        $regularHours = round($totalRegularMins / 60, 2);
        $overtimeHours = round($totalOvertimeMins / 60, 2);
        
        $totalAccumulated = $this->includeOvertimeInTotal 
            ? ($regularHours + $overtimeHours) 
            : $regularHours;

        $progressPercentage = min(100, ($totalAccumulated / $this->targetHours) * 100);

        return view('livewire.ojt.blog-manager', [
            'weeklyData' => $weeklyData, // We now pass a single master array!
            'regularHours' => $regularHours,
            'overtimeHours' => $overtimeHours,
            'totalAccumulated' => $totalAccumulated,
            'progressPercentage' => $progressPercentage
        ]);
    }
}