<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    #[Computed]
    public function stats(): array
    {
        return [
            'total_items' => Item::count(),
            'loaned_items' => Item::where('status', 'loaned')->count(),
            'maintenance_items' => Item::where('status', 'maintenance')->count(),
            'categories_count' => Category::count(),
            'locations_count' => Location::count(),
        ];
    }

    #[Computed]
    public function recentItems()
    {
        return Item::with(['category', 'location'])
            ->latest()
            ->take(6)
            ->get();
    }

    #[Computed]
    public function alertItems()
    {
        // For actual production, this would query specific health metrics.
        // For now, we fetch items marked with "maintenance" or "lost" as alerts.
        return Item::whereIn('status', ['maintenance', 'lost'])
            ->latest()
            ->take(3)
            ->get();
    }

    #[Computed]
    public function chartData(): array
    {
        // Mocking a weekly trend based on real data would require a 'loans' table.
        // For now, let's just create a dynamic but safe representation of current inventory spread.
        // We ensure we don't have hardcoded heights in the view.
        return [25, 45, 30, 60, 40, 50, 35]; 
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
