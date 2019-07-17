<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

trait ActiveUserHelper
{
    // 用于存放临时用户
    protected $users = [];

    // 配置信息
    protected $topic_weight = 4;    // 一个帖子 4分
    protected $reply_weight = 1;    // 一条留言 1分
    protected $pass_days = 7;       // 一周内
    protected $user_number = 6;     // 取出6个用户

    // 缓存配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_seconds = 65 * 60;

    public function getActiveUsers()
    {
        // 尝试从缓存中去除数据，如果有，则直接返回数据
        // 否则运行匿名函数中的代码从数据库获取活跃用户，返回的同时做缓存
        return \Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function () {
            return $this->calculateActiveUsers();
        });
    }

    public function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        // 辅助函数  https://learnku.com/docs/laravel/5.8/helpers/3919#method-array-sort  二维数组排序 升序排序
        $users = Arr::sort($this->users, function ($user) {
            return $user['score'];
        });

        $users = array_reverse($users, true); // https://www.runoob.com/php/func-array-reverse.html
        $users = array_slice($users, 0, $this->user_number, true); // https://www.runoob.com/php/func-array-slice.html
        $users = collect($users);

        $active_users = collect();
        foreach ($users as $user_id => $score) {
            $user = $this->find($user_id);
            if ($user) {
                $active_users->push($user);
            }
        }
        return $active_users;
    }

    private function calculateTopicScore()
    {

        $topic_users = DB::table('topics')   // 这里的 DB::table('topics')->select() 和 Topic::select()有啥区别呢？？
            ->select(DB::raw('user_id, count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        foreach ($topic_users as $val) {
            $this->users[$val->user_id]['score'] = $val->topic_count * $this->topic_weight;
        }
    }

    private function calculateReplyScore()
    {
        // 获取 $pass_days 范围内，发表过留言的用户； 并且获取此段时间内发布回复的数量
        $reply_users = DB::table('replies')
            ->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        foreach ($reply_users as $val) {
            $reply_score = $val->reply_count * $this->reply_weight;
            if (isset($this->users[$val->user_id])) {
                $this->users[$val->user_id]['score'] += $reply_score;
            } else {
                $this->users[$val->user_id]['score'] = $reply_score;
            }
        }
    }

    // 计算得出活跃用户，并存入缓存
    public function calculateAndCacheActiveUsers()
    {
        $active_users = $this->calculateActiveUsers();
        $this->cacheActiveUsers($active_users);
    }

    // 将数据存入缓存
    public function cacheActiveUsers($active_users)
    {
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_seconds);
    }
}