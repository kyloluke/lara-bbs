<?php

namespace App\Observers;

use App\Jobs\TranslateSlugJob;
use App\Models\Topic;

class TopicObserver
{
    // creating, created, updating, updated, saving,
    // saved,  deleting, deleted, restoring, restored

    // 方法接收 model 作为其唯一的参数
    public function saving(Topic $topic)
    {
        // 生成话题摘要，meta 标签需要此值
        $topic->excerpt = make_excerpt($topic->body);

        // xss 攻击
        $topic->body = clean($topic->body, 'topic_body');
        if (empty($topic->body)) {
            $topic->body = '作者是SB';
        }
    }

    // 模型监控器的 saved() 方法对应 Eloquent 的 saved 事件，此事件发生在创建和编辑时、数据入库以后。在 saved() 方法中调用，确保了我们在分发任务时，$topic->id 永远有值。
    public function saved(Topic $topic)
    {
        // 如果 $topic->slug 无内容，则翻译 标题  以下两种判断都是 ok 的
        // $topic-getOriginal('title') 获取保存之前的值
        // $topic->isDirty('title')    即将保存的值与旧值不同返回 true
        // if (!$topic->slug || $topic->isDirty('title')) {
        if (!$topic->slug || $topic->getOriginal('title') != $topic->title) {
            dispatch(new TranslateSlugJob($topic));
        }
    }
}
