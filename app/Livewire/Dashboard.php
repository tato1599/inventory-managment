<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\InventoryAdjustment;
use App\Models\Loan;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    #[Computed]
    public function stats(): array
    {
        return [
            'total_items' => Item::sum('quantity'),
            'loaned_items' => Item::where('status', 'loaned')->count(),
            'maintenance_items' => Item::where('status', 'maintenance')->count(),
            'overdue_loans' => Loan::whereNull('returned_at')->where('due_at', '<', now())->count(),
        ];
    }

    #[Computed]
    public function recentActivity()
    {
        return InventoryAdjustment::with(['item', 'user', 'newLocation'])
            ->latest()
            ->take(6)
            ->get();
    }

    #[Computed]
    public function topLocations()
    {
        return Location::withCount('items')
            ->orderByDesc('items_count')
            ->take(4)
            ->get();
    }

    #[Computed]
    public function alertItems()
    {
        // Items with overdue loans or marked as lost
        return Item::where('status', 'lost')
            ->orWhereHas('loans', function($q) {
                $q->whereNull('returned_at')->where('due_at', '<', now());
            })
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function chartData(): array
    {
        // Real weekly trend of loans registered
        $days = collect(range(0, 6))->map(fn($i) => now()->subDays($i)->format('Y-m-d'))->reverse();
        
        $data = Loan::select(DB::raw('DATE(loaned_at) as date'), DB::raw('count(*) as count'))
            ->where('loaned_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date');

        return $days->map(fn($date) => $data->get($date, 0))->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
