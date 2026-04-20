<?php

namespace App\Livewire\Inventory;

use App\Models\Item;
use App\Models\Category;
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
    public ?Item $selectedItem = null;

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
        return Item::with('category')
            ->when($this->search, function (Builder $query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('sku', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->when($this->categoryId, function (Builder $query) {
                $query->where('category_id', $this->categoryId);
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
    public function headers(): array
    {
        return [
            ['key' => 'sku', 'label' => 'SKU', 'class' => 'w-32'],
            ['key' => 'name', 'label' => 'Nombre'],
            ['key' => 'category.name', 'label' => 'Departamento', 'class' => 'hidden lg:table-cell'],
            ['key' => 'status', 'label' => 'Estado', 'class' => 'w-32'],
            ['key' => 'actions', 'label' => '', 'sortable' => false],
        ];
    }

    public function showDetails(int $id): void
    {
        $this->selectedItem = Item::with('category')->findOrFail($id);
        $this->showDrawer = true;
    }

    public function closeDrawer(): void
    {
        $this->showDrawer = false;
        $this->selectedItem = null;
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
