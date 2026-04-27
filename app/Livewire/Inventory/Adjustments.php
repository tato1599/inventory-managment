<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryAdjustment;
use Livewire\Component;
use Livewire\WithPagination;

class Adjustments extends Component
{
    use WithPagination;

    public string $search = '';
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function clearFilters(): void
    {
        $this->reset('search');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'item.name', 'label' => 'Activo'],
            ['key' => 'user.name', 'label' => 'Administrador'],
            ['key' => 'type', 'label' => 'Tipo'],
            ['key' => 'notes', 'label' => 'Motivo'],
            ['key' => 'created_at', 'label' => 'Fecha'],
        ];
    }

    public function adjustments()
    {
        return InventoryAdjustment::with(['item', 'user', 'oldLocation', 'newLocation'])
            ->when($this->search, function ($query) {
                $query->whereHas('item', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                })->orWhere('notes', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.inventory.adjustments', [
            'rows' => $this->adjustments(),
            'headers' => $this->headers()
        ])->layout('layouts.app');
    }
}
