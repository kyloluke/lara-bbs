<?php

namespace App\Observers;

use App\Events\News;
use App\Models\Reply;
use App\Notifications\TopicReplied;

class ReplyObserver
{
    // https://learnku.com/docs/laravel/5.8/eloquent/3931#observers
    public function created(Reply $reply)
    {
        // 更新文章的留言数量
        // $reply->topic()->increment('reply_count', 1);  下方的方法比较严谨
        $reply->topic->updateReplyCount();

        // xss 攻击
        $reply->content = clean($reply->content, 'topic_body');
        if (empty($reply->content)) {
            $reply->content = '站长，我爱你~~';
            $reply->save();
        }

        // 广播
        broadcast(new News($reply->load('user', 'topic.user')));

        // 通知文章作者
        $reply->topic->user->notify(new TopicReplied($reply));
    }

    public function deleted(Reply $reply)
    {
        $reply->topic->updateReplyCount();
    }
}
