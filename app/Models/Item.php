<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'location_id',
        'sku',
        'name',
        'description',
        'quantity',
        'status',
        'is_loanable',
        'loan_type',
        'max_loan_duration',
        'image_url',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'is_loanable' => 'boolean',
    ];

    /**
     * Get the category that owns the item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the location where the item is stored.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the loans for the item.
     */
    public function loans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get the active loan for the item.
     */
    public function getCurrentLoanAttribute(): ?Loan
    {
        return $this->loans()->whereNull('returned_at')->latest()->first();
    }

    /**
     * Get the adjustments for the item.
     */
    public function adjustments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryAdjustment::class);
    }
}
