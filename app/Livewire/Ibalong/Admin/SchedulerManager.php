<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\IbalongHackathon;
use App\Models\IbalongActivity;
use App\Models\IbalongActivityTrack;
use App\Models\IbalongActivitySlot;
use App\Models\IbalongUser;
use App\Models\IbalongRegistration;
use App\Models\IbalongAppointment;

class SchedulerManager extends Component
{
    public $activeHackathon;
    
    // UI Modal State
    public $showForgeActivityModal = false;
    public $showRandomAssignModal = false;

    // Forge Activity State
    public $activityTitle = '';
    public $activityType = 'mentorship';
    public $activityDescription = '';

    // Edit Activity State
    public $editingActivityId = null;
    public $editActivityTitle = '';
    public $editActivityType = '';
    public $editActivityDescription = '';

    // Hub Forge State
    public $selectedActivityId = null;
    public $trackName = '';
    public $mentorId = ''; 
    public $location = '';
    public $slotDate = '';
    public $startTime = '';
    public $endTime = '';
    public $durationMinutes = 30;

    // Hub Editing & Extension State
    public $editingTrackId = null;
    public $editTrackName = '';
    public $editMentorId = '';
    public $editLocation = '';
    public $appendSlotDate = '';
    public $appendStartTime = '';
    public $appendEndTime = '';
    public $appendDurationMinutes = 30;

    // Manual Assignment State
    public $assigningSlotId = null;
    public $assignTeamId = '';

    // Command Override State (Mentor Assessments)
    public $overrideAppointmentId = null;
    public $overrideTeamName = '';
    public $overrideStatus = 'booked';
    public $overrideNotes = '';

    // Auto-Assign Draft State
    public $randomAssignActivityId = null;
    public $selectedTeamsForRandom = [];
    public $draftAssignments = [];
    public $draftPreview = [];

    // Nuclear Reset State
    public $resettingActivityId = null;

    // --- NEW: UNIVERSAL ADMIN INTERCEPTOR STATE ---
    public $requiresAdminAuth = false;
    public $adminAuthEmail = '';
    public $adminAuthPassword = '';
    public $pendingActionMethod = '';
    public $pendingActionParams = [];
    public $adminAuthorizedSession = false;

    public function mount()
    {
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) { abort(403); }

        $this->activeHackathon = IbalongHackathon::firstOrCreate(
            ['status' => 'active'],
            ['name' => 'Heroes of Innovation 2026']
        );
    }

    // --- THE INTERCEPTOR LOGIC ---
    protected function checkAuthorization($method, $params = [], $forceForAdmins = false)
    {
        $role = auth('ibalong')->user()->role_id;
        $isAdmin = in_array($role, [1, 2]);

        // If user is Admin and we aren't forcing auth (like for nuclear resets), let them through
        if ($isAdmin && !$forceForAdmins) {
            return true;
        }

        // If we just successfully passed the auth interceptor, let them through
        if ($this->adminAuthorizedSession) {
            return true;
        }

        // User is a facilitator (or it's a forced auth action). Trigger Interceptor.
        $this->pendingActionMethod = $method;
        $this->pendingActionParams = $params;
        $this->requiresAdminAuth = true;
        $this->adminAuthEmail = '';
        $this->adminAuthPassword = '';
        return false;
    }

    public function processAdminAuth()
    {
        $this->validate([
            'adminAuthEmail' => 'required|email',
            'adminAuthPassword' => 'required'
        ]);

        $admin = IbalongUser::where('email', $this->adminAuthEmail)
            ->whereIn('role_id', [1, 2])
            ->first();

        if (!$admin || !Hash::check($this->adminAuthPassword, $admin->password)) {
            session()->flash('auth_error', 'SYSTEM REJECT: Invalid Administrator Credentials.');
            return;
        }

        // Authorization Success!
        $this->adminAuthorizedSession = true;
        $this->requiresAdminAuth = false;

        // Dynamically execute the suspended action
        if ($this->pendingActionMethod) {
            call_user_func_array([$this, $this->pendingActionMethod], $this->pendingActionParams);
        }

        // Lock it back down immediately after execution
        $this->adminAuthorizedSession = false;
        $this->pendingActionMethod = '';
        $this->pendingActionParams = [];
    }

    // --- ACTIVITY PROTOCOLS ---
    public function openForgeActivityModal()
    {
        $this->reset(['activityTitle', 'activityType', 'activityDescription']);
        $this->showForgeActivityModal = true;
    }

    public function createActivity()
    {
        $this->validate([
            'activityTitle' => 'required|string|max:255',
            'activityType' => 'required|string',
        ]);

        if (!$this->checkAuthorization('createActivity')) return;

        $this->activeHackathon->activities()->create([
            'title' => $this->activityTitle,
            'type' => $this->activityType,
            'description' => $this->activityDescription,
            'is_published' => false
        ]);

        $this->reset(['activityTitle', 'activityDescription']);
        $this->showForgeActivityModal = false;
        session()->flash('success', 'Activity framework established.');
    }

    public function openEditActivity($id)
    {
        $activity = IbalongActivity::findOrFail($id);
        $this->editingActivityId = $activity->id;
        $this->editActivityTitle = $activity->title;
        $this->editActivityType = $activity->type;
        $this->editActivityDescription = $activity->description;
    }

    public function updateActivity()
    {
        $this->validate([
            'editActivityTitle' => 'required|string|max:255',
            'editActivityType' => 'required|string',
        ]);

        if (!$this->checkAuthorization('updateActivity')) return;

        IbalongActivity::findOrFail($this->editingActivityId)->update([
            'title' => $this->editActivityTitle,
            'type' => $this->editActivityType,
            'description' => $this->editActivityDescription,
        ]);

        $this->editingActivityId = null;
        session()->flash('success', 'Activity parameters successfully updated.');
    }

    public function togglePublish($id)
    {
        if (!$this->checkAuthorization('togglePublish', [$id])) return;

        $activity = IbalongActivity::findOrFail($id);
        $activity->update(['is_published' => !$activity->is_published]);
    }

    public function toggleBooking($id)
    {
        // Protected by the Admin Password Interceptor!
        if (!$this->checkAuthorization('toggleBooking', [$id])) return;

        $activity = IbalongActivity::findOrFail($id);
        $activity->update(['allow_booking' => !$activity->allow_booking]);
    }

    // --- HUB GENERATION PROTOCOLS ---
    public function openTrackGenerator($activityId)
    {
        $this->selectedActivityId = $activityId;
        $this->reset(['trackName', 'mentorId', 'location', 'slotDate', 'startTime', 'endTime', 'durationMinutes']);
    }

    public function generateTrackAndSlots()
    {
        $this->validate([
            'trackName' => 'required|string',
            'mentorId' => 'nullable',
            'slotDate' => 'required|date',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
            'durationMinutes' => 'required|integer|min:10',
        ]);

        if (!$this->checkAuthorization('generateTrackAndSlots')) return;

        $track = IbalongActivityTrack::create([
            'activity_id' => $this->selectedActivityId,
            'name' => $this->trackName,
            'location' => $this->location,
            'mentor_id' => $this->mentorId ?: null,
        ]);

        $start = Carbon::parse($this->slotDate . ' ' . $this->startTime);
        $end = Carbon::parse($this->slotDate . ' ' . $this->endTime);
        $duration = (int) $this->durationMinutes;

        $current = $start->copy();

        while ($current < $end) {
            $slotEnd = $current->copy()->addMinutes($duration);
            if ($slotEnd > $end) { break; }

            IbalongActivitySlot::create([
                'track_id' => $track->id,
                'start_time' => $current,
                'end_time' => $slotEnd,
                'capacity' => 1
            ]);
            $current = $slotEnd;
        }

        $this->selectedActivityId = null;
        session()->flash('success', 'Hub forged and time blocks successfully generated.');
    }

    // --- HUB EDIT & EXTENSION PROTOCOLS ---
    public function openEditTrack($trackId)
    {
        $track = IbalongActivityTrack::findOrFail($trackId);
        $this->editingTrackId = $track->id;
        $this->editTrackName = $track->name;
        $this->editLocation = $track->location;
        $this->editMentorId = $track->mentor_id;

        $this->reset(['appendSlotDate', 'appendStartTime', 'appendEndTime']);
        $this->appendDurationMinutes = 30;
    }

    public function updateTrack()
    {
        $this->validate([
            'editTrackName' => 'required|string',
            'editMentorId' => 'nullable',
            'editLocation' => 'nullable|string',
        ]);

        if ($this->appendSlotDate || $this->appendStartTime || $this->appendEndTime) {
             $this->validate([
                 'appendSlotDate' => 'required|date',
                 'appendStartTime' => 'required',
                 'appendEndTime' => 'required|after:appendStartTime',
                 'appendDurationMinutes' => 'required|integer|min:10',
             ]);
        }

        if (!$this->checkAuthorization('updateTrack')) return;

        $track = IbalongActivityTrack::findOrFail($this->editingTrackId);
        $track->update([
            'name' => $this->editTrackName,
            'location' => $this->editLocation,
            'mentor_id' => $this->editMentorId ?: null,
        ]);

        if ($this->appendSlotDate && $this->appendStartTime && $this->appendEndTime) {
            $start = Carbon::parse($this->appendSlotDate . ' ' . $this->appendStartTime);
            $end = Carbon::parse($this->appendSlotDate . ' ' . $this->appendEndTime);
            $duration = (int) $this->appendDurationMinutes;

            $current = $start->copy();
            $blocksAdded = 0;

            while ($current < $end) {
                $slotEnd = $current->copy()->addMinutes($duration);
                if ($slotEnd > $end) { break; }

                $exists = IbalongActivitySlot::where('track_id', $track->id)
                    ->where('start_time', $current)
                    ->exists();

                if (!$exists) {
                    IbalongActivitySlot::create([
                        'track_id' => $track->id,
                        'start_time' => $current,
                        'end_time' => $slotEnd,
                        'capacity' => 1
                    ]);
                    $blocksAdded++;
                }

                $current = $slotEnd;
            }

            session()->flash('success', "Hub updated and {$blocksAdded} new time block(s) successfully appended.");
        } else {
            session()->flash('success', 'Hub parameters have been updated.');
        }

        $this->editingTrackId = null;
    }

    // --- DELETION PROTOCOLS ---
    public function deleteTrack($trackId)
    {
        if (!$this->checkAuthorization('deleteTrack', [$trackId])) return;

        IbalongActivityTrack::findOrFail($trackId)->delete();
        session()->flash('success', 'Hub and all associated time blocks have been purged.');
    }

    public function removeSlot($slotId)
    {
        if (!$this->checkAuthorization('removeSlot', [$slotId])) return;

        $slot = IbalongActivitySlot::with('appointments')->findOrFail($slotId);

        if ($slot->appointments->count() > 0) {
            session()->flash('error', 'SYSTEM LOCK: Cannot purge a time block that contains assigned cohorts.');
            return;
        }

        $slot->delete();
        session()->flash('success', 'Empty time block successfully purged.');
    }

    public function removeAppointment($appointmentId)
    {
        if (!$this->checkAuthorization('removeAppointment', [$appointmentId])) return;

        IbalongAppointment::findOrFail($appointmentId)->delete();
        session()->flash('success', 'Appointment successfully wiped from the hub.');
    }

    // --- OVERRIDE PROTOCOLS (Manual Assignment & Assessment overrides) ---
    public function openAssignModal($slotId)
    {
        $this->assigningSlotId = $slotId;
        $this->assignTeamId = '';
    }

    public function assignTeamToSlot()
    {
        $this->validate([
            'assignTeamId' => 'required',
        ]);

        if (!$this->checkAuthorization('assignTeamToSlot')) return;

        $slot = IbalongActivitySlot::with('appointments')->findOrFail($this->assigningSlotId);

        if ($slot->appointments->count() >= $slot->capacity) {
            session()->flash('error', 'SYSTEM ALERT: This time block is already at maximum capacity.');
            return;
        }

        $alreadyAssigned = IbalongAppointment::where('slot_id', $this->assigningSlotId)
            ->where('team_id', $this->assignTeamId)
            ->exists();

        if ($alreadyAssigned) {
            session()->flash('error', 'SYSTEM ALERT: Cohort is already assigned to this specific block.');
            return;
        }

        IbalongAppointment::create([
            'slot_id' => $this->assigningSlotId,
            'team_id' => $this->assignTeamId,
            'status' => 'booked'
        ]);

        $this->assigningSlotId = null;
        $this->assignTeamId = '';
        session()->flash('success', 'Command Override Successful: Cohort forcibly injected into time block.');
    }

    public function openOverrideModal($appointmentId)
    {
        $appointment = IbalongAppointment::with('team')->findOrFail($appointmentId);
        
        $this->overrideAppointmentId = $appointment->id;
        $this->overrideTeamName = $appointment->team->team_name ?? 'Unknown Cohort';
        $this->overrideStatus = $appointment->status;
        $this->overrideNotes = $appointment->notes;
    }

    public function saveOverrideAssessment()
    {
        $this->validate([
            'overrideStatus' => 'required|in:booked,attended,no_show,cancelled',
            'overrideNotes' => 'nullable|string'
        ]);

        if (!$this->checkAuthorization('saveOverrideAssessment')) return;

        $appointment = IbalongAppointment::findOrFail($this->overrideAppointmentId);
        
        $appointment->update([
            'status' => $this->overrideStatus,
            'notes' => $this->overrideNotes
        ]);

        $this->overrideAppointmentId = null;
        session()->flash('success', "Command Override: Assessment logs for {$this->overrideTeamName} have been rewritten.");
    }

    // --- ROUND-ROBIN CIRCUIT DRAFT ALGORITHM ---
    public function openRandomAssignModal($activityId)
    {
        $this->randomAssignActivityId = $activityId;
        $this->selectedTeamsForRandom = [];
        $this->draftAssignments = [];
        $this->draftPreview = [];
        $this->showRandomAssignModal = true;
    }

    public function generateRandomDraft()
    {
        $this->validate([
            'selectedTeamsForRandom' => 'required|array|min:1'
        ], [
            'selectedTeamsForRandom.required' => 'You must select at least one cohort to generate an assignment matrix.'
        ]);

        // Drafting is allowed without auth because it doesn't change the database yet.
        $activity = IbalongActivity::with('tracks.slots.appointments')->findOrFail($this->randomAssignActivityId);
        $hubs = $activity->tracks;
        $numHubs = $hubs->count();
        $numTeams = count($this->selectedTeamsForRandom);

        if ($numTeams < $numHubs) {
            session()->flash('draft_error', "SYSTEM REJECT: You selected {$numTeams} teams, but there are {$numHubs} active hubs. You need at least as many teams as hubs to fill a concurrent timeslot without duplicating a team.");
            return;
        }

        $allSlots = $hubs->flatMap->slots->sortBy('start_time');
        $uniqueTimes = $allSlots->pluck('start_time')->unique(function($time) { return $time->format('H:i'); })->values();
        $numTimeslots = $uniqueTimes->count();

        if ($numTimeslots > $numTeams) {
            session()->flash('draft_error', "SYSTEM REJECT: You have {$numTimeslots} timeslots but only {$numTeams} teams. To prevent a team from visiting the exact same hub twice, you must select more teams or shorten the event timeframe.");
            return;
        }

        $shuffledTeams = collect($this->selectedTeamsForRandom)->shuffle()->toArray();
        $this->draftAssignments = [];
        $this->draftPreview = [];

        foreach ($uniqueTimes as $timeIndex => $time) {
            $sortedHubs = $hubs->sortBy('id')->values();

            foreach ($sortedHubs as $hubIndex => $track) {
                $slot = $track->slots->first(function($s) use ($time) {
                    return $s->start_time->format('H:i') === $time->format('H:i');
                });

                if ($slot) {
                    $teamIndex = ($hubIndex - $timeIndex) % $numTeams;
                    if ($teamIndex < 0) { $teamIndex += $numTeams; } 

                    $assignedTeamId = $shuffledTeams[$teamIndex];
                    $team = IbalongRegistration::find($assignedTeamId);

                    $this->draftAssignments[] = [
                        'slot_id' => $slot->id,
                        'team_id' => $assignedTeamId
                    ];

                    $this->draftPreview[] = [
                        'team_name' => $team->team_name,
                        'hub_name' => $track->name,
                        'date' => $slot->start_time->format('M d, Y'),
                        'time' => $slot->start_time->format('h:i A') . ' - ' . $slot->end_time->format('h:i A')
                    ];
                }
            }
        }
    }

    public function discardDraft()
    {
        $this->draftAssignments = [];
        $this->draftPreview = [];
    }

    public function commitRandomAssignments()
    {
        if (!$this->checkAuthorization('commitRandomAssignments')) return;

        $commits = 0;
        foreach($this->draftAssignments as $assignment) {
            $slot = IbalongActivitySlot::with('appointments')->find($assignment['slot_id']);
            $alreadyBooked = $slot->appointments->where('team_id', $assignment['team_id'])->count() > 0;
            $hasCapacity = $slot->appointments->count() < $slot->capacity;

            if(!$alreadyBooked && $hasCapacity) {
                IbalongAppointment::create([
                    'slot_id' => $assignment['slot_id'],
                    'team_id' => $assignment['team_id'],
                    'status' => 'booked'
                ]);
                $commits++;
            }
        }

        $this->showRandomAssignModal = false;
        $this->randomAssignActivityId = null;
        $this->selectedTeamsForRandom = [];
        $this->draftAssignments = [];
        $this->draftPreview = [];

        session()->flash('success', "Circuit Matrix Executed. {$commits} cohorts successfully hardcoded into the timeframe.");
    }

    // --- NUCLEAR PURGE PROTOCOL ---
    public function openResetSchedulesModal($activityId)
    {
        $this->resettingActivityId = $activityId;
    }

    public function executeSchedulesReset()
    {
        // Notice the 'true' parameter! This forces even Admins to provide their password to prevent misclicks on data-destructive actions.
        if (!$this->checkAuthorization('executeSchedulesReset', [], true)) return;

        $activity = IbalongActivity::with('tracks.slots.appointments')->findOrFail($this->resettingActivityId);
        
        $appointmentIds = $activity->tracks->flatMap->slots->flatMap->appointments->pluck('id');
        IbalongAppointment::whereIn('id', $appointmentIds)->delete();

        $this->resettingActivityId = null;

        session()->flash('success', 'Nuclear Purge Complete: All cohort schedules and mentor notes for this activity have been wiped clean.');
    }

    // --- EXTRACTION PROTOCOLS ---
    public function exportMatrixCsv($activityId)
    {
        $activity = IbalongActivity::with(['tracks.mentor', 'tracks.slots.appointments.team'])
            ->findOrFail($activityId);
            
        $fileName = 'matrix_' . \Illuminate\Support\Str::slug($activity->title) . '_' . date('Ymd_His') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $allSlots = $activity->tracks->flatMap->slots->sortBy('start_time');
        $uniqueTimes = $allSlots->pluck('start_time')->unique(function($time) { return $time->format('H:i'); });
        
        $callback = function() use ($activity, $uniqueTimes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['SYSTEM MATRIX: ' . strtoupper($activity->title)]);
            fputcsv($file, ['']); 
            
            $header1 = ['TIME BLOCK'];
            foreach($activity->tracks as $track) {
                $header1[] = strtoupper($track->name);
            }
            fputcsv($file, $header1);
            
            $header2 = ['MENTOR / FACILITATOR'];
            foreach($activity->tracks as $track) {
                $header2[] = strtoupper($track->mentor->name ?? 'UNASSIGNED');
            }
            fputcsv($file, $header2);
            
            foreach($uniqueTimes as $time) {
                $row = [$time->format('h:i A')];
                
                foreach($activity->tracks as $track) {
                    $slot = $track->slots->first(function($s) use ($time) { 
                        return $s->start_time->format('H:i') === $time->format('H:i'); 
                    });
                    
                    if ($slot && $slot->appointments->count() > 0) {
                        $teams = $slot->appointments->map(function($apt) {
                            return $apt->team->team_name ?? 'Unknown';
                        })->implode(' & ');
                        $row[] = strtoupper($teams);
                    } elseif ($slot) {
                        $row[] = '[ OPEN SLOT ]';
                    } else {
                        $row[] = '---';
                    }
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };
        
        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function render()
    {
        $activities = IbalongActivity::with(['tracks.mentor', 'tracks.slots' => function($q){
                $q->orderBy('start_time', 'asc');
            }, 'tracks.slots.appointments.team'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->latest()
            ->get();

        $mentors = IbalongUser::whereIn('role_id', [1, 2, 4, 6])
            ->orderBy('name', 'asc')
            ->get();

        $teams = IbalongRegistration::where('status', 'approved')
            ->orderBy('team_name', 'asc')
            ->get();

        return view('livewire.ibalong.admin.scheduler-manager', compact('activities', 'mentors', 'teams'))
            ->layout('layouts.dashboard');
    }
}