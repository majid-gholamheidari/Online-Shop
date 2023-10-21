<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCategory extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'title',
        'img',
        'description',
        'slug',
        'category_id',
        'status',
        'level'
    ];


    // ==================  relations  ====================

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StoreCategory::class, 'category_id', 'id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StoreCategory::class, 'category_id', 'id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StoreProduct::class, 'category_id', 'id');
    }


    // =================  functions  ===================
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = str_replace(' ', '-', $model->title);
        });
    }

    public function getFaStatusAttribute(): string
    {
        return match ($this->status) {
            'active' => 'فعال',
            'inactive' => 'غیر فعال',
            default => "نامشخص"
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'green',
            'inactive' => 'red',
            default => "orange"
        };
    }
}
