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

    public function mount($username)
    {
        $this->student = User::where('username', $username)->firstOrFail();
    }

    // Flips the database state for a single specific time log
    public function toggleRowOvertime($logId)
    {
        // Security check: only edit logs belonging to this specific student
        $log = TimeLog::where('user_id', $this->student->id)->find($logId);

        if ($log) {
            $log->is_overtime_approved = !$log->is_overtime_approved;
            $log->save();
        }
    }

    public function render()
    {
        $timeLogs = TimeLog::where('user_id', $this->student->id)->get();
        $blogs = OjtBlog::where('user_id', $this->student->id)->get();

        $weeklyData = [];
        $grandTotalCredited = 0;
        $grandTotalRaw = 0;

        // 1. Group Time Logs by Week & Calculate Manually
        foreach ($timeLogs as $log) {
            $date = Carbon::parse($log->log_date);
            $weekStart = $date->copy()->startOfWeek();
            $weekKey = $weekStart->format('Y-m-d');

            if (!isset($weeklyData[$weekKey])) {
                $weeklyData[$weekKey] = $this->initializeWeekData($weekStart);
            }

            // RUN MANUAL MATH ON THE FLY
            $creditedMins = $this->calculateClampedMinutes($log);
            $rawMins = $this->calculateRawMinutes($log);

            // Attach the math to the log object so the Blade view can use it
            $log->credited_minutes = $creditedMins;
            $log->raw_minutes = $rawMins;

            $weeklyData[$weekKey]['logs'][] = $log;
            $weeklyData[$weekKey]['total_credited'] += $creditedMins;
            $weeklyData[$weekKey]['total_raw'] += $rawMins;

            $grandTotalCredited += $creditedMins;
            $grandTotalRaw += $rawMins;
        }

        // 2. Group Blogs by Week
        foreach ($blogs as $blog) {
            $date = Carbon::parse($blog->report_date);
            $weekStart = $date->copy()->startOfWeek();
            $weekKey = $weekStart->format('Y-m-d');

            if (!isset($weeklyData[$weekKey])) {
                $weeklyData[$weekKey] = $this->initializeWeekData($weekStart);
            }
            $weeklyData[$weekKey]['blogs'][] = $blog;
        }

        krsort($weeklyData); // Sort newest to oldest

        return view('livewire.ojt.coordinator-view', [
            'weeklyData' => $weeklyData,
            'grandTotalHours' => round($grandTotalCredited / 60, 2),
            'grandTotalRawHours' => round($grandTotalRaw / 60, 2),
        ])->layout('layouts.guest'); // Change layout to webmaster if you prefer!
    }

    private function initializeWeekData($weekStart)
    {
        return [
            'label' => $weekStart->format('M d') . ' - ' . $weekStart->copy()->endOfWeek()->format('M d, Y'),
            'logs' => [],
            'blogs' => [],
            'total_credited' => 0,
            'total_raw' => 0,
        ];
    }

    // ==========================================
    // MANUAL CALCULATION ENGINES
    // ==========================================

    private function calculateClampedMinutes($log)
    {
        // Use setTime() to avoid Carbon double-time parsing errors
        $officeOpen = Carbon::parse($log->log_date)->setTime(8, 0, 0);
        $officeClose = Carbon::parse($log->log_date)->setTime(17, 0, 0);

        // Check the database for this specific row!
        $isOvertimeAllowed = $log->is_overtime_approved;

        $clamp = function ($time) use ($officeOpen, $officeClose, $isOvertimeAllowed) {
            if (!$time) return null;
            $t = Carbon::parse($time);

            // 1. ALWAYS clamp early arrivals to 8:00 AM
            if ($t->lessThan($officeOpen)) return $officeOpen->copy();

            // 2. ONLY clamp late departures if THIS SPECIFIC SHIFT isn't approved for OT
            if (!$isOvertimeAllowed && $t->greaterThan($officeClose)) return $officeClose->copy();

            return $t;
        };

        $minutes = 0;
        if ($log->morning_in && $log->morning_out) {
            $mIn = $clamp($log->morning_in);
            $mOut = $clamp($log->morning_out);
            if ($mOut->greaterThan($mIn)) $minutes += $mIn->diffInMinutes($mOut);
        }
        if ($log->afternoon_in && $log->afternoon_out) {
            $aIn = $clamp($log->afternoon_in);
            $aOut = $clamp($log->afternoon_out);
            if ($aOut->greaterThan($aIn)) $minutes += $aIn->diffInMinutes($aOut);
        }
        return $minutes;
    }

    private function calculateRawMinutes($log)
    {
        $minutes = 0;
        if ($log->morning_in && $log->morning_out) {
            $minutes += Carbon::parse($log->morning_in)->diffInMinutes(Carbon::parse($log->morning_out));
        }
        if ($log->afternoon_in && $log->afternoon_out) {
            $minutes += Carbon::parse($log->afternoon_in)->diffInMinutes(Carbon::parse($log->afternoon_out));
        }
        return $minutes;
    }
}
