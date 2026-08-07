<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductSku extends Model
{
    protected $fillable = [
        'product_id',
        'sku_code',
        'selling_price',
        'mrp',
        'cost_price',
        'stock',
        'low_stock_alert',
        'barcode',
        'weight',
        'length',
        'width',
        'height',
        'status',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'sku_attribute_values', 'sku_id', 'attribute_value_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'sku_id')->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class, 'sku_id')->where('is_primary', true);
    }

    /**
     * Real-life scenario: an order can't be filled if stock is short. Callers
     * must check this before decrementing stock at checkout instead of
     * silently clamping to zero.
     */
    public function hasStockFor(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }
}
