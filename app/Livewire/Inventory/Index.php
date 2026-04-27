<?php

namespace App\Livewire\Inventory;

use App\Models\Item;
use App\Models\Loan;
use App\Models\InventoryAdjustment;
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
    public bool $auditMode = false;
    public ?Item $selectedItem = null;
    public array $auditData = [];

    // Form data (New Item)
    public array $newItem = [
        'name' => '',
        'sku' => '',
        'category_id' => null,
        'location_id' => null,
        'description' => '',
        'quantity' => 1,
        'status' => 'available',
        'is_loanable' => true,
        'loan_type' => 'daily',
        'max_loan_duration' => null,
    ];

    // Form data (Loan)
    public array $loanData = [
        'borrower_name' => '',
        'borrower_id_number' => '',
        'due_at' => '',
        'notes' => ''
    ];

    // Form data (Adjustment)
    public bool $showAdjustmentDrawer = false;
    public array $adjustmentData = [
        'new_status' => '',
        'new_location_id' => null,
        'new_quantity' => null,
        'notes' => ''
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
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('sku', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                });
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
            ['key' => 'quantity', 'label' => 'Existencia', 'class' => 'w-20 text-center'],
            ['key' => 'category.name', 'label' => 'Departamento', 'class' => 'hidden lg:table-cell'],
            ['key' => 'location.name', 'label' => 'Ubicación', 'class' => 'hidden md:table-cell'],
            ['key' => 'is_loanable', 'label' => 'Prestable', 'class' => 'w-24 text-center'],
            ['key' => 'status', 'label' => 'Estado', 'class' => 'w-32'],
            ['key' => 'actions', 'label' => '', 'sortable' => false],
        ];
    }

    public function showDetails(int $id): void
    {
        $this->selectedItem = Item::with(['category', 'location', 'loans', 'adjustments.user', 'adjustments.oldLocation', 'adjustments.newLocation'])->findOrFail($id);
        $this->showDrawer = true;
    }

    public function openAdjustment(): void
    {
        if (!$this->selectedItem) return;
        
        $this->adjustmentData = [
            'new_status' => $this->selectedItem->status,
            'new_location_id' => $this->selectedItem->location_id,
            'new_quantity' => $this->selectedItem->quantity,
            'notes' => ''
        ];
        $this->showAdjustmentDrawer = true;
    }

    public function makeAdjustment(): void
    {
        $this->validate([
            'adjustmentData.new_status' => 'required|in:available,loaned,maintenance,lost',
            'adjustmentData.new_location_id' => 'nullable|exists:locations,id',
            'adjustmentData.new_quantity' => 'required|integer|min:0',
            'adjustmentData.notes' => 'required|string|min:5',
        ], [
            'adjustmentData.notes.required' => 'El motivo del ajuste es obligatorio.',
            'adjustmentData.notes.min' => 'Por favor explica brevemente el motivo.'
        ]);

        $oldStatus = $this->selectedItem->status;
        $oldLocationId = $this->selectedItem->location_id;
        $oldQuantity = $this->selectedItem->quantity;

        InventoryAdjustment::create([
            'item_id' => $this->selectedItem->id,
            'user_id' => auth()->id(),
            'type' => ($oldQuantity != $this->adjustmentData['new_quantity']) ? 'correction' : (($oldStatus != $this->adjustmentData['new_status']) ? 'status_change' : 'location_transfer'),
            'old_status' => $oldStatus,
            'new_status' => $this->adjustmentData['new_status'],
            'old_location_id' => $oldLocationId,
            'new_location_id' => $this->adjustmentData['new_location_id'],
            'old_quantity' => $oldQuantity,
            'new_quantity' => $this->adjustmentData['new_quantity'],
            'notes' => $this->adjustmentData['notes'],
        ]);

        $this->selectedItem->update([
            'status' => $this->adjustmentData['new_status'],
            'location_id' => $this->adjustmentData['new_location_id'],
            'quantity' => $this->adjustmentData['new_quantity'],
        ]);

        $this->dispatch('mary-toast', [
            'title' => 'Inventario Ajustado',
            'description' => "Se ha registrado el ajuste para {$this->selectedItem->name}.",
            'type' => 'success'
        ]);

        $this->showAdjustmentDrawer = false;
        $this->selectedItem->refresh();
    }

    public function registerLoan(): void
    {
        if (!$this->selectedItem) return;

        $this->validate([
            'loanData.borrower_name' => 'required|string|min:3',
            'loanData.borrower_id_number' => 'nullable|string',
            'loanData.due_at' => 'nullable|date|after:now',
        ], [
            'loanData.borrower_name.required' => 'El nombre del beneficiario es obligatorio.',
            'loanData.due_at.after' => 'La fecha de entrega debe ser posterior a ahora.'
        ]);

        $loan = Loan::create([
            'item_id' => $this->selectedItem->id,
            'user_id' => auth()->id(),
            'borrower_name' => $this->loanData['borrower_name'],
            'borrower_id_number' => $this->loanData['borrower_id_number'],
            'loaned_at' => now(),
            'due_at' => $this->loanData['due_at'] ?: null,
            'notes' => $this->loanData['notes'],
        ]);

        $this->selectedItem->update(['status' => 'loaned']);

        $this->dispatch('mary-toast', [
            'title' => 'Préstamo Registrado',
            'description' => "El equipo ha sido entregado a {$loan->borrower_name}.",
            'type' => 'success'
        ]);

        $this->reset('loanData');
        $this->selectedItem->refresh();
    }

    public function returnItem(): void
    {
        if (!$this->selectedItem) return;

        $loan = $this->selectedItem->current_loan;
        if ($loan) {
            $loan->update(['returned_at' => now()]);
        }

        $this->selectedItem->update(['status' => 'available']);

        $this->dispatch('mary-toast', [
            'title' => 'Equipo Devuelto',
            'description' => "El equipo {$this->selectedItem->name} está disponible nuevamente.",
            'type' => 'success'
        ]);

        $this->selectedItem->refresh();
    }

    public function saveItem(): void
    {
        $validated = $this->validate([
            'newItem.name' => 'required|min:3',
            'newItem.sku' => 'required|unique:items,sku',
            'newItem.category_id' => 'required|exists:categories,id',
            'newItem.location_id' => 'nullable|exists:locations,id',
            'newItem.description' => 'nullable|string',
            'newItem.quantity' => 'required|integer|min:1',
            'newItem.status' => 'required|in:available,loaned,maintenance,lost',
            'newItem.is_loanable' => 'boolean',
            'newItem.loan_type' => 'required|in:daily,hourly',
            'newItem.max_loan_duration' => 'nullable|integer|min:1'
        ], [
            'newItem.name.required' => 'El nombre es obligatorio.',
            'newItem.sku.required' => 'El SKU es obligatorio.',
            'newItem.sku.unique' => 'Este SKU ya existe en el sistema.',
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

    public function clearFilters(): void
    {
        $this->reset(['search', 'categoryId']);
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

    public function markAsVerified(int $id): void
    {
        $item = Item::findOrFail($id);
        
        InventoryAdjustment::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'type' => 'correction',
            'old_status' => $item->status,
            'new_status' => $item->status,
            'old_location_id' => $item->location_id,
            'new_location_id' => $item->location_id,
            'old_quantity' => $item->quantity,
            'new_quantity' => $item->quantity,
            'notes' => 'Verificación en Inventario Cíclico',
        ]);

        $this->dispatch('mary-toast', [
            'title' => 'Ítem Verificado',
            'description' => "{$item->name} ha sido confirmado en stock.",
            'type' => 'success'
        ]);

        $this->auditData[$id] = true;
    }

    public function toggleAuditMode(): void
    {
        $this->auditMode = !$this->auditMode;
        if (!$this->auditMode) {
            $this->reset('auditData');
        }
    }

    public function render()
    {
        return view('livewire.inventory.index')
            ->layout('layouts.app');
    }
}
