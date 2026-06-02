<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id', 'path', 'alt_text', 'sort_order', 'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute()
    {
        if (!$this->path) return 'https://placehold.co/600x800/e2e8f0/64748b?text=No+Image';
        if (filter_var($this->path, FILTER_VALIDATE_URL)) return $this->path;
        return \Illuminate\Support\Facades\Storage::url($this->path);
    }
}
