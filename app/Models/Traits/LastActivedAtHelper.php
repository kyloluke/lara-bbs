<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;
use App\Models\User;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'last_actived_at_';

    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // 今天日期 当前时间，如：2019-10-21
        $today = Carbon::now()->toDateString();
        $hash = $this->hash_prefix . $today;

        $field = $this->field_prefix . $this->id;

        // 当前时间 如：2019-10-21 08:35:15
        $now = Carbon::now()->toDateTimeString();

        Redis::hSet($hash, $field, $now);
    }

    public function syncActivedUserAt()
    {
        // 获取昨天日期
        // $yesterday = Carbon::yesterday()->toDateString();
        $yesterday = Carbon::now()->toDateString();

        $hash = $this->hash_prefix . $yesterday;

        // 获取昨天的数据
        $yesterdayData = Redis::hGetAll($hash);
        // 遍历存入数据库
        foreach ($yesterdayData as $key => $val) {
            $user_id = str_replace($this->field_prefix, '', $key);
            if ($user = User::find($user_id)) {
                $user->last_actived_at = $val;
                $user->save();
            }
        }

        Redis::del($hash);
    }
}
