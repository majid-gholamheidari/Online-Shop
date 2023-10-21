<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreTag extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'title'
    ];
}
