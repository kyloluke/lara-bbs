<?php

namespace App\Models;

class Category extends Model
{
    protected $fillable = [
        'name', 'description', 'post_count'
    ];

    // 该模型不需要自动维护时间戳
    public $timestamps = false;

}
