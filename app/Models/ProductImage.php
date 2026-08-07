<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = ['sku_id', 'image_path', 'title', 'alt_text', 'sort_order', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'sku_id');
    }
}
