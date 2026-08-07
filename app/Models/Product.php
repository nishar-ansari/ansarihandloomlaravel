<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'brand_id',
        'attribute_set_id', 'description', 'short_description',
        'meta_title', 'meta_description', 'tags', 'status'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributeSet(): BelongsTo
    {
        return $this->belongsTo(AttributeSet::class);
    }

    public function skus(): HasMany
    {
        return $this->hasMany(ProductSku::class);
    }

    public function defaultSku(): HasOne
    {
        return $this->hasOne(ProductSku::class)->where('is_default', true);
    }

    /**
     * Full photo gallery across every variant (images live on the SKU, not
     * the product - see docx section 6). Useful for admin "all photos" views.
     */
    public function images(): HasManyThrough
    {
        return $this->hasManyThrough(ProductImage::class, ProductSku::class, 'product_id', 'sku_id')
            ->orderBy('product_images.sort_order');
    }

    /**
     * The thumbnail shown on listing/grid pages before a variant is chosen:
     * the primary photo of the merchandiser-picked default SKU.
     */
    public function primaryImage(): HasOneThrough
    {
        return $this->hasOneThrough(ProductImage::class, ProductSku::class, 'product_id', 'sku_id')
            ->where('product_skus.is_default', true)
            ->where('product_images.is_primary', true);
    }

    public function globalAttributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }
}
