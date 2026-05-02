<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class UpdatePostScores extends Command
{
    protected $signature = 'posts:update-scores';
    protected $description = 'Recalculate popularity scores for recent posts';

    public function handle()
    {
        $this->info('Starting score calculation...');

        // Only process posts from the last 14 days to save server CPU
        Post::where('created_at', '>=', now()->subDays(14))
            ->withCount(['elements', 'comments']) // Get the counts efficiently
            ->chunkById(100, function ($posts) {
                foreach ($posts as $post) {
                    // Time decay: How many hours old is this post?
                    $hoursOld = max(1, $post->created_at->diffInHours(now()));

                    // The Algorithm: 
                    // 1 point per reaction, 3 points per comment
                    // Subtract 0.5 points for every hour it has been alive
                    $score = ($post->elements_count * 1) 
                           + ($post->comments_count * 3) 
                           - ($hoursOld * 0.5);

                    // Prevent scores from dropping below zero
                    $finalScore = max(0, $score);

                    // Only update if the score actually changed to save DB writes
                    if ($post->popularity_score != $finalScore) {
                        $post->update(['popularity_score' => $finalScore]);
                    }
                }
            });

        $this->info('Scores updated successfully!');
    }
}