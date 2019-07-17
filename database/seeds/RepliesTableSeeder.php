<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Topic;
use App\Models\Reply;

class RepliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取 faker 实例
        $faker = app(\Faker\Generator::class);

        // 获取所有文章的 ids
        $topic_ids = Topic::all()->pluck('id')->toArray();


        // 获取所有用户的 ids
        $user_ids = User::all()->pluck('id')->toArray();

        $replies = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function($item) use($faker, $user_ids, $topic_ids) {
                $item->user_id = $faker->randomElement($user_ids);
                $item->topic_id = $faker->randomElement($topic_ids);
            })->toArray();

        Reply::insert($replies);
    }
}
