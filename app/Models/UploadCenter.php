<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadCenter extends Model
{


    protected $fillable = [
        'alt',
        'path',
        'size',
        'ext',
        'model'
    ];
}
