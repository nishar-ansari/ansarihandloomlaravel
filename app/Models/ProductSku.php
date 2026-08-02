<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status'
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
}
