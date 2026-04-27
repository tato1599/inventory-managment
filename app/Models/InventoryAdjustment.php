<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'type',
        'old_status',
        'new_status',
        'old_location_id',
        'new_location_id',
        'old_quantity',
        'new_quantity',
        'notes',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oldLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'old_location_id');
    }

    public function newLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'new_location_id');
    }
}
