<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'mrp',
        'discount',
        'stock',
    ];

    protected $casts = [
        'mrp' => 'decimal:2',
        'discount' => 'integer',
        'stock' => 'integer',
    ];

    protected $appends = ['price'];

    public function getPriceAttribute()
    {
        if (!$this->mrp) return 0;
        return round((float)$this->mrp * (1 - ($this->discount / 100)));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
