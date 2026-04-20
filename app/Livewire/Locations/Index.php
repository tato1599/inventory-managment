<?php

namespace App\Livewire\Locations;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use WithPagination;

    // Search and UI State
    public string $search = '';
    public int $perPage = 10;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public bool $createDrawer = false;

    // Form data
    public array $newLocation = [
        'name' => '',
        'code' => '',
        'description' => ''
    ];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function locations(): LengthAwarePaginator
    {
        return Location::withCount('items')
            ->when($this->search, function (Builder $query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('code', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Nombre de Ubicación'],
            ['key' => 'code', 'label' => 'Código', 'class' => 'w-32 font-mono'],
            ['key' => 'items_count', 'label' => 'Artículos', 'class' => 'w-32 text-center'],
            ['key' => 'actions', 'label' => '', 'sortable' => false],
        ];
    }

    public function save(): void
    {
        $this->validate([
            'newLocation.name' => 'required|min:2',
            'newLocation.code' => 'nullable|unique:locations,code',
            'newLocation.description' => 'nullable'
        ], [
            'newLocation.name.required' => 'El nombre es obligatorio.',
            'newLocation.code.unique' => 'Este código ya está en uso.'
        ]);

        $location = Location::create($this->newLocation);

        $this->dispatch('mary-toast', [
            'title' => 'Ubicación Registrada',
            'description' => "{$location->name} ha sido añadida correctamente.",
            'type' => 'success'
        ]);

        $this->reset(['newLocation', 'createDrawer']);
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $location = Location::withCount('items')->findOrFail($id);
        
        if ($location->items_count > 0) {
            $this->dispatch('mary-toast', [
                'title' => 'Acceso Denegado',
                'description' => "No se puede eliminar una ubicación que tiene artículos asignados.",
                'type' => 'error'
            ]);
            return;
        }

        $location->delete();
        
        $this->dispatch('mary-toast', [
            'title' => 'Ubicación eliminada',
            'description' => "La ubicación ha sido removida.",
            'type' => 'success'
        ]);
        
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.locations.index')
            ->layout('layouts.app');
    }
}
