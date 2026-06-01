<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Project; 
use App\Models\SiteStat; 
use Illuminate\Support\Facades\Session; 

#[Layout('layouts.madya-template')]
class ProjectsShow extends Component
{
    public Project $project;
    public $visitorCount = 1;
    
    public $canViewEvaluationResults = false; 
    
    public $totalResponses = 0;
    public $overallRating = 0.0;
    // [UPDATED] Replaced $likertCriteriaScores with a grouped array
    public $groupedLikertResults = []; 

    public function mount(Project $project)
    {
        $this->project = $project;
        
        if (!Session::has('has_visited_site')) {
            SiteStat::where('key', 'visitor_count')->increment('value');
            Session::put('has_visited_site', true);
        }
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');

        $this->project->load([
            'category', 
            'objectives', 
            'galleries',
            'sdgs',
            'academicYear',
            'proponents.user',
            'projectLinkages.linkage',
            'evaluation.collaborators',
            'evaluation.questions.answers' 
        ]);

        // [UPDATED] Group the Likert Results by Section
        if ($this->project->evaluation) {
            $eval = $this->project->evaluation;
            $this->totalResponses = $eval->responses()->count();
            
            if ($this->totalResponses > 0) {
                $sumOfAverages = 0;
                $likertCount = 0;
                
                $grouped = [];
                $currentSectionTitle = 'General Evaluation';

                foreach ($eval->questions->sortBy('order') as $question) {
                    
                    if ($question->type === 'section') {
                        $currentSectionTitle = strip_tags(\Illuminate\Support\Str::markdown($question->question_text ?? ''));
                    } 
                    elseif ($question->type === 'likert') {
                        $flatOptions = collect($question->options)->map(fn($opt) => is_array($opt) ? ($opt['text'] ?? '') : $opt)->toArray();
                        $sum = 0;
                        $count = 0;
                        
                        foreach ($question->answers as $answer) {
                            $index = array_search($answer->answer_value, $flatOptions);
                            if ($index !== false) {
                                $sum += ($index + 1); 
                                $count++;
                            }
                        }
                        
                        if ($count > 0) {
                            $avg = $sum / $count;
                            $sumOfAverages += $avg;
                            $likertCount++;
                            
                            // Initialize the section if it doesn't exist yet
                            if (!isset($grouped[$currentSectionTitle])) {
                                $grouped[$currentSectionTitle] = [
                                    'title' => $currentSectionTitle,
                                    'criteria' => [],
                                    'sum' => 0,
                                    'count' => 0
                                ];
                            }
                            
                            $grouped[$currentSectionTitle]['criteria'][] = [
                                'label' => strip_tags(\Illuminate\Support\Str::markdown($question->question_text ?? '')),
                                'score' => round($avg, 1)
                            ];
                            $grouped[$currentSectionTitle]['sum'] += $avg;
                            $grouped[$currentSectionTitle]['count']++;
                        }
                    }
                }
                
                if ($likertCount > 0) {
                    $this->overallRating = round($sumOfAverages / $likertCount, 1);
                    
                    // Calculate the average for each section and sort criteria
                    foreach ($grouped as &$group) {
                        $group['section_average'] = round($group['sum'] / $group['count'], 1);
                        // Sort criteria highest to lowest inside their section
                        usort($group['criteria'], fn($a, $b) => $b['score'] <=> $a['score']);
                    }
                    
                    $this->groupedLikertResults = array_values($grouped);
                }
            }
        }

        // Security Gate for the Results Dashboard Link
        if (auth()->check() && $this->project->evaluation) {
            $user = auth()->user();
            $role = $user->role?->role_name;
            $eval = $this->project->evaluation;

            $isCollaborator = $eval->collaborators->contains('id', $user->id);

            if (in_array($role, ['administrator', 'director']) || $eval->created_by === $user->id || $isCollaborator) {
                $this->canViewEvaluationResults = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.director.projects-show');
    }
}