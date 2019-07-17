<?php

namespace App\Models;

use App\Models\User;
use App\Models\Topic;

class Reply extends Model
{
    protected $fillable = ['content'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
