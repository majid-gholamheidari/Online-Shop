<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'description',
        'status',
        'price',
        'discount_price',
        'discount_percent',
        'stock',
        'category_id',
        'brand_id',
        'images',
        'cover_img',
        'tags',
        'colors',
        'properties',
        'attributes',
        'special_offer',
        'special_offer_from',
        'special_offer_to',
        'slug',
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'colors' => 'array',
        'attributes' => 'array',
        'properties' => 'array',
    ];

    // =================== functions =======================

    public static function codeGenerator(): int
    {
        $code = rand(10000000, 99999999);
        if (StoreProduct::whereCode($code)->exists()) {
            return self::codeGenerator();
        }
        return $code;
    }

    public function getFaStatusAttribute(): string
    {
        return match ($this->status) {
            'active' => 'فعال',
            'inactive' => 'غیر فعال',
        };
    }

    // =================== relations =======================

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StoreCategory::class, 'category_id', 'id');
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StoreBrand::class, 'brand_id', 'id');
    }

}
