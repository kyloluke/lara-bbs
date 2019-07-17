<?php

namespace App\Models;


class Topic extends Model
{
    protected $fillable = [
        'title', 'body', 'category_id', 'order', 'excerpt', 'slug'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // 不容的排序，不同的数据获取逻辑
        switch ($order) {
            case 'recent';
                $query->recent();
                break;

            default;
                $query->recentReplied();
                break;
        }
        return $query->with('user', 'category');    // 顺便解决 n+1 问题
    }

    public function scopeRecentReplied($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function link($params = [])
    {
        // 参考 array_merge() https://www.php.net/manual/zh/function.array-merge.php
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Reply', 'topic_id');
    }
    
    // 更新 reply_count 字段
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }
}
