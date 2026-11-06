<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'quantity',
        'reorder_level',
        'unit_price',
        'supplier',
    ];

    // Relationship: All transactions
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Relationship: Latest transaction
    public function latestTransaction(): HasOne
    {
        return $this->hasOne(InventoryTransaction::class)->latestOfMany();
    }

    // Check if stock is low
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level
            && $this->quantity > 0;
    }

    // Check if product is out of stock
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    // Stock status accessor
    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        }

        if ($this->isLowStock()) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    // Stock status color accessor
    public function getStockStatusColorAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'danger';
        }

        if ($this->isLowStock()) {
            return 'warning';
        }

        return 'success';
    }

    // Total stock in
    public function getTotalStockInAttribute(): int
    {
        return (int) $this->transactions()
            ->where('type', 'stock_in')
            ->sum('quantity');
    }

    // Total stock out
    public function getTotalStockOutAttribute(): int
    {
        return (int) $this->transactions()
            ->where('type', 'stock_out')
            ->sum('quantity');
    }

    // Low stock scope
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'reorder_level')
            ->where('quantity', '>', 0);
    }

    // Out of stock scope
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    // Filter products by category
    public function scopeInCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
