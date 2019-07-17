<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
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
        // 获取 users
        $users = User::all()->pluck('id')->toArray();

        // 获取 categories
        $categories = Category::all()->pluck('id')->toArray();

        $topics = factory(Topic::class)
                ->times(100)
                ->make()
                ->each(function($item) use($faker, $users, $categories) {
                    $item->user_id = $faker->randomElement($users);
                    $item->category_id = $faker->randomElement($categories);
                });
        // 批量插入数据
        Topic::insert($topics->toArray());
    }
}
