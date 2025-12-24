<?php

namespace App\Livewire\Open\News;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Index extends Component
{
    public $search = '';
    public $category = 'All Stories';

    public function setCategory($cat)
    {
        $this->category = $cat;
    }

    public function render()
    {
        // Simulated Data (In a real app, this comes from the Database)
        $allNews = [
            ['id' => 1, 'title' => 'Tree Planting Initiative in Albay', 'cat' => 'Environment', 'date' => 'Dec 12, 2024', 'img' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb7d5763?q=80&w=1000'],
            ['id' => 2, 'title' => 'Digital Literacy Workshop Success', 'cat' => 'Education', 'date' => 'Nov 28, 2024', 'img' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=1000'],
            ['id' => 3, 'title' => 'Call for New Members: A.Y. 2025', 'cat' => 'Announcement', 'date' => 'Oct 15, 2024', 'img' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1000'],
            ['id' => 4, 'title' => 'Cultural Heritage Month Exhibit', 'cat' => 'Culture', 'date' => 'Sep 05, 2024', 'img' => 'https://images.unsplash.com/photo-1461301214746-1e790926d323?q=80&w=1000'],
            ['id' => 5, 'title' => 'Partnership with UNESCO Signed', 'cat' => 'Achievement', 'date' => 'Aug 20, 2024', 'img' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1000'],
            ['id' => 6, 'title' => 'Student Mental Health Forum', 'cat' => 'Social Science', 'date' => 'Aug 10, 2024', 'img' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?q=80&w=1000'],
        ];

        // Filter Logic
        $news = collect($allNews)->filter(function ($item) {
            // 1. Filter by Search
            $matchesSearch = empty($this->search) || stripos($item['title'], $this->search) !== false;
            
            // 2. Filter by Category
            $matchesCategory = $this->category === 'All Stories' || $item['cat'] === $this->category;

            return $matchesSearch && $matchesCategory;
        });

        return view('livewire.open.news.index', [
            'news' => $news
        ]);
    }
}
