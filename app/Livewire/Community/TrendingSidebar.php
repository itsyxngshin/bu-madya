<?php

namespace App\Livewire\Community;

use Livewire\Component;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class TrendingSidebar extends Component
{
    public function render()
    {
        // Cache the results for 1 hour (3600 seconds) to prevent database strain
        $trendingTags = Cache::remember('trending_topics', 3600, function () {
            return Tag::withCount(['posts' => function ($query) {
                    // Only count posts created in the last 7 days to keep it "trending"
                    $query->where('created_at', '>=', now()->subDays(7));
                }])
                // Ensure we only grab tags that actually have recent posts
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->limit(5)
                ->get();
        });

        return view('livewire.community.trending-sidebar', [
            'trendingTags' => $trendingTags
        ]);
    }
}