<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Category;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_items' => Item::count(),
            'loaned_items' => Item::where('status', 'loaned')->count(),
            'maintenance_items' => Item::where('status', 'maintenance')->count(),
            'categories_count' => Category::count(),
        ];

        $recentItems = Item::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'recentItems' => $recentItems,
        ])->layout('layouts.app');
    }
}
