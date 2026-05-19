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

    public function render()
    {
        $timeLogs = TimeLog::where('user_id', $this->student->id)->get();
        $blogs = OjtBlog::where('user_id', $this->student->id)->get();

        // Map blogs by date to attach narratives to raw DTR logs
        $blogsByDate = $blogs->keyBy(function($b) {
            return Carbon::parse($b->report_date)->format('Y-m-d');
        });

        $weeklyData = [];
        $grandTotalCredited = 0;
        $grandTotalRaw = 0;

        // 1. Process and group DTR Time Logs
        foreach ($timeLogs as $log) {
            $date = Carbon::parse($log->log_date);
            $weekStart = $date->copy()->startOfWeek();
            $weekKey = $weekStart->format('Y-m-d');

            if (!isset($weeklyData[$weekKey])) {
                $weeklyData[$weekKey] = $this->initializeWeekData($weekStart);
            }

            $logDateString = $date->format('Y-m-d');
            $log->associated_blog = $blogsByDate[$logDateString] ?? null;

            // Run manual math conversions
            $creditedMins = $this->calculateClampedMinutes($log);
            $rawMins = $this->calculateRawMinutes($log);

            $log->credited_minutes = $creditedMins;
            $log->raw_minutes = $rawMins;

            $weeklyData[$weekKey]['logs'][] = $log;
            $weeklyData[$weekKey]['total_credited'] += $creditedMins;
            $weeklyData[$weekKey]['total_raw'] += $rawMins;

            $grandTotalCredited += $creditedMins;
            $grandTotalRaw += $rawMins;
        }

        // 2. Process and group Journal Entries + Media Attachments
        foreach ($blogs as $blog) {
            $date = Carbon::parse($blog->report_date);
            $weekStart = $date->copy()->startOfWeek();
            $weekKey = $weekStart->format('Y-m-d');

            if (!isset($weeklyData[$weekKey])) {
                $weeklyData[$weekKey] = $this->initializeWeekData($weekStart);
            }
            
            $weeklyData[$weekKey]['blogs'][] = $blog;

            // Extract valid photo paths for the weekly collage pool
            if ($blog->attachment_path) {
                $weeklyData[$weekKey]['photos'][] = [
                    'url'   => asset('storage/' . $blog->attachment_path),
                    'title' => $blog->title,
                    'date'  => $date->format('M d')
                ];
            }
        }

        krsort($weeklyData); // Order weeks newest to oldest

        return view('livewire.ojt.coordinator-view', [
            'weeklyData'         => $weeklyData,
            'grandTotalHours'    => round($grandTotalCredited / 60, 2),
            'grandTotalRawHours' => round($grandTotalRaw / 60, 2),
        ])->layout('layouts.guest');
    }

    private function initializeWeekData($weekStart)
    {
        return [
            'label'          => $weekStart->format('M d') . ' - ' . $weekStart->copy()->endOfWeek()->format('M d, Y'),
            'logs'           => [],
            'blogs'          => [],
            'photos'         => [], // <-- Assembles current week's photos
            'total_credited' => 0,
            'total_raw'      => 0,
        ];
    }

    private function calculateClampedMinutes($log)
    {
        // Define the strict boundaries of the OJT shifts
        $morningStart   = Carbon::parse($log->log_date)->setTime(8, 0, 0);
        $morningEnd     = Carbon::parse($log->log_date)->setTime(12, 0, 0);
        $afternoonStart = Carbon::parse($log->log_date)->setTime(13, 0, 0); // 1:00 PM
        $afternoonEnd   = Carbon::parse($log->log_date)->setTime(17, 0, 0); // 5:00 PM

        $isOvertimeAllowed = $log->is_overtime_approved;
        $minutes = 0;

        // 1. Process Morning Shift (Strictly clamped to 8 AM - 12 PM)
        if ($log->morning_in && $log->morning_out) {
            $mIn = Carbon::parse($log->morning_in);
            $mOut = Carbon::parse($log->morning_out);

            // Clamp early arrivals to 8:00 AM
            if ($mIn->lessThan($morningStart)) $mIn = $morningStart->copy();
            
            // Clamp late lunch outs to exactly 12:00 PM
            if ($mOut->greaterThan($morningEnd)) $mOut = $morningEnd->copy();

            if ($mOut->greaterThan($mIn)) {
                $minutes += $mIn->diffInMinutes($mOut);
            }
        }

        // 2. Process Afternoon Shift (Strictly starts at 1 PM)
        if ($log->afternoon_in && $log->afternoon_out) {
            $aIn = Carbon::parse($log->afternoon_in);
            $aOut = Carbon::parse($log->afternoon_out);

            // Clamp early afternoon returns to exactly 1:00 PM
            if ($aIn->lessThan($afternoonStart)) $aIn = $afternoonStart->copy();
            
            // Clamp late departures to exactly 5:00 PM (UNLESS Overtime is approved)
            if (!$isOvertimeAllowed && $aOut->greaterThan($afternoonEnd)) {
                $aOut = $afternoonEnd->copy();
            }

            if ($aOut->greaterThan($aIn)) {
                $minutes += $aIn->diffInMinutes($aOut);
            }
        }

        return $minutes;
    }

    private function calculateRawMinutes($log)
    {
        // Raw minutes remain strictly based on actual punches (no clamps applied)
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