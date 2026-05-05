<?php

namespace App\Livewire\Ojt;

use Livewire\Component;
use App\Models\User;
use App\Models\TimeLog;
use App\Models\OjtBlog;
use Carbon\Carbon;

class CoordinatorView extends Component
{
    public $student;

    // We pass the username through the route: e.g., yoursite.com/ojt/adornado
    public function mount($username)
    {
        $this->student = User::where('username', $username)->firstOrFail();
    }

    public function render()
    {
        // 1. Fetch all records for the student
        $timeLogs = TimeLog::where('user_id', $this->student->id)->get();
        $blogs = OjtBlog::where('user_id', $this->student->id)->get();

        $weeklyData = [];

        // 2. Group Time Logs by Week
        foreach ($timeLogs as $log) {
            $date = Carbon::parse($log->log_date);
            $weekStart = $date->copy()->startOfWeek();
            $weekKey = $weekStart->format('Y-m-d'); // Used for sorting

            if (!isset($weeklyData[$weekKey])) {
                $weeklyData[$weekKey] = $this->initializeWeekData($weekStart);
            }

            $weeklyData[$weekKey]['logs'][] = $log;
            $weeklyData[$weekKey]['total_minutes'] += $log->total_minutes_rendered;
        }

        // 3. Group Blogs by Week
        foreach ($blogs as $blog) {
            $date = Carbon::parse($blog->report_date);
            $weekStart = $date->copy()->startOfWeek();
            $weekKey = $weekStart->format('Y-m-d');

            if (!isset($weeklyData[$weekKey])) {
                $weeklyData[$weekKey] = $this->initializeWeekData($weekStart);
            }

            $weeklyData[$weekKey]['blogs'][] = $blog;
        }

        // 4. Sort from newest week to oldest week
        krsort($weeklyData);

        return view('livewire.ojt.coordinator-view', [
            'weeklyData' => $weeklyData,
            'grandTotalHours' => round($timeLogs->sum('total_minutes_rendered') / 60, 2)
        ])->layout('layouts.guest'); // Use a guest layout so no login is required
    }

    private function initializeWeekData($weekStart)
    {
        return [
            'label' => $weekStart->format('M d') . ' - ' . $weekStart->copy()->endOfWeek()->format('M d, Y'),
            'logs' => [],
            'blogs' => [],
            'total_minutes' => 0,
        ];
    }
}
