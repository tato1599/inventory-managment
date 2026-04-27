<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/inventory', \App\Livewire\Inventory\Index::class)->name('inventory.index');
    Route::get('/inventory/adjustments', \App\Livewire\Inventory\Adjustments::class)->name('inventory.adjustments');
    Route::get('/inventory/print-labels', [\App\Http\Controllers\Inventory\LabelController::class, 'print'])->name('inventory.print-labels');
    Route::get('/locations', \App\Livewire\Locations\Index::class)->name('locations.index');
});
