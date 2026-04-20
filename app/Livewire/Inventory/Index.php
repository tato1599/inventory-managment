<?php

namespace App\Livewire\Inventory;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Index extends Component
{
    use WithPagination;

    // Search and filters
    public string $search = '';
    public ?int $categoryId = null;
    
    // UI State
    public int $perPage = 10;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public array $selectedIds = [];
    public bool $showDrawer = false;
    public bool $createDrawer = false;
    public ?Item $selectedItem = null;

    // Form data (New Item)
    public array $newItem = [
        'name' => '',
        'sku' => '',
        'category_id' => null,
        'location_id' => null,
        'description' => '',
        'status' => 'available'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => null],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        return Item::with(['category', 'location'])
            ->when($this->search, function (Builder $query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('sku', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    #[Computed]
    public function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    #[Computed]
    public function locations(): Collection
    {
        return Location::orderBy('name')->get();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'sku', 'label' => 'SKU', 'class' => 'w-32'],
            ['key' => 'name', 'label' => 'Nombre'],
            ['key' => 'category.name', 'label' => 'Departamento', 'class' => 'hidden lg:table-cell'],
            ['key' => 'location.name', 'label' => 'Ubicación', 'class' => 'hidden md:table-cell'],
            ['key' => 'status', 'label' => 'Estado', 'class' => 'w-32'],
            ['key' => 'actions', 'label' => '', 'sortable' => false],
        ];
    }

    public function showDetails(int $id): void
    {
        $this->selectedItem = Item::with(['category', 'location'])->findOrFail($id);
        $this->showDrawer = true;
    }

    public function saveItem(): void
    {
        $validated = $this->validate([
            'newItem.name' => 'required|min:3',
            'newItem.sku' => 'required|unique:items,sku',
            'newItem.category_id' => 'required|exists:categories,id',
            'newItem.location_id' => 'nullable|exists:locations,id',
            'newItem.description' => 'nullable|string',
            'newItem.status' => 'required|in:available,loaned,maintenance,lost'
        ], [
            'newItem.name.required' => 'El nombre es obligatorio.',
            'newItem.sku.required' => 'El SKU es obligatorio.',
            'newItem.sku.unique' => 'Este SKU ya existe en el Nodo NCIE.',
            'newItem.category_id.required' => 'Debes seleccionar un departamento.'
        ]);

        $item = Item::create($this->newItem);

        $this->dispatch('mary-toast', [
            'title' => 'Artículo Creado',
            'description' => "{$item->name} se ha añadido al inventario.",
            'type' => 'success'
        ]);

        $this->reset(['newItem', 'createDrawer']);
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $item = Item::findOrFail($id);
        $item->delete();
        
        $this->dispatch('mary-toast', [
            'title' => 'Artículo eliminado',
            'description' => "El artículo {$item->name} ha sido eliminado correctamente.",
            'type' => 'success'
        ]);
        
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.inventory.index')
            ->layout('layouts.app');
    }
}
