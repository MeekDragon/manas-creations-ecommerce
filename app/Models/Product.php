<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'tagline',
        'description',
        'images',
        'rating',
        'reviews_count',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'rating' => 'decimal:1',
    ];

    // ── Relationships ────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // ── Accessors ────────────────────────────────

    /** Returns the first image URL, or null */
    public function getPrimaryImageAttribute(): ?string
    {
        $images = $this->images ?? [];
        return count($images) > 0 ? $images[0] : null;
    }

    // ── Scopes ───────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
