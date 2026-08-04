<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IbalongEvaluation;
use App\Models\IbalongEvaluationResponse;

class EvaluationForm extends Component
{
    use WithFileUploads;

    public IbalongEvaluation $evaluation;
    public $answers = [];
    public $files = [];
    public $isSubmitted = false;

    public function mount($slug)
    {
        $this->evaluation = IbalongEvaluation::with(['questions' => function($q) {
            $q->orderBy('order', 'asc');
        }])->where('slug', $slug)->firstOrFail();

        if (!$this->evaluation->is_active) {
            abort(403, 'SYSTEM HALT: This form is currently offline.');
        }

        // NEW: Enforce Access Control Protocol
        if ($this->evaluation->access_level === 'teams_only' && !isset(auth('ibalong')->user()->registration)) {
            abort(403, 'CLEARANCE DENIED: This evaluation is strictly restricted to registered cohorts.');
        }

        // Initialize array for checkboxes to prevent Livewire string conflict errors
        foreach ($this->evaluation->questions as $q) {
            if ($q->type === 'checkbox') {
                $this->answers[$q->id] = [];
            }
        }
    }

    protected function rules()
    {
        $rules = [];
        foreach ($this->evaluation->questions as $q) {
            if (in_array($q->type, ['section', 'page_break'])) continue;

            $baseRule = $q->is_required ? 'required' : 'nullable';

            if ($q->type === 'file') {
                $rules["files.{$q->id}"] = "$baseRule|file|max:5120"; // 5MB limit
            } elseif ($q->type === 'checkbox') {
                $rules["answers.{$q->id}"] = "$baseRule|array";
            } else {
                $rules["answers.{$q->id}"] = "$baseRule";
            }
        }
        return $rules;
    }

    public function submit()
    {
        $this->validate();

        $response = IbalongEvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => auth('ibalong')->id(),
            'team_id' => auth('ibalong')->user()->registration->id ?? null,
        ]);

        foreach ($this->evaluation->questions as $q) {
            if (in_array($q->type, ['section', 'page_break'])) continue;

            $val = null;

            if ($q->type === 'file' && isset($this->files[$q->id])) {
                // Sanitize file names
                $file = $this->files[$q->id];
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $sanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $originalName) ?: 'upload';
                $finalName = $sanitizedName . '_' . uniqid() . '.' . $extension;

                $val = $file->storeAs("ibalong-evaluations/responses/{$response->id}", $finalName, 'public');
            } elseif ($q->type === 'checkbox') {
                $val = json_encode($this->answers[$q->id] ?? []);
            } else {
                $val = $this->answers[$q->id] ?? null;
            }

            if ($val !== null && $val !== '' && $val !== '[]') {
                $response->answers()->create([
                    'question_id' => $q->id,
                    'answer_value' => $val,
                ]);
            }
        }

        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.ibalong.evaluation-form')->layout('layouts.dashboard');
    }
}
