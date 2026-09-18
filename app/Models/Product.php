<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Shop;
use App\Models\Category;

#[Fillable(['code', 'name', 'category_id', 'price', 'description'])]
class Product extends Model
{
    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class)->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }
}
