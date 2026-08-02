<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = [
        'name', 'code', 'input_type', 'is_required', 
        'is_searchable', 'is_filterable', 'is_variant', 
        'sort_order', 'status'
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('id');
    }

    public function attributeSets(): BelongsToMany
    {
        return $this->belongsToMany(AttributeSet::class, 'attribute_set_attributes');
    }
}
