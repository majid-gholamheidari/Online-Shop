<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreBrand extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'title',
        'en_title',
        'status',
        'slug',
        'img'
    ];
}
