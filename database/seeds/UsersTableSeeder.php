<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
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

        // 头像假数据
        $avatars = [
            'https://cdn.learnku.com/uploads/images/201710/14/1/s5ehp11z6s.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/Lhd1SHqu86.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/LOnMrqbHJn.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/xAuDMxteQy.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/NDnzMutoxX.png',
        ];

        // 生成数据集合
        // factory(User::class) 根据指定的 User 生成模型工厂构造器，对应加载 UserFactory.php 中的工厂设置
        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($item) use ($faker, $avatars) {
                $item->avatar = $faker->randomElement($avatars);
            });

        // 让隐藏字段可见，并将数据集合转换为数组 此操作确保入库时数据库不会报错。
        $users_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 批量创建用户
        User::insert($users_array);

        // 单独处理第一个用户
        $user = User::findOrFail(1);
        $user->name = '管理员';
        $user->email = '460474426@qq.com';
        $user->password = bcrypt('secret');
        $user->avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user->save();

        // 将 1号 用户指派为站长   assignRole  是在  HasRoles Trait 中定义的
        $user->assignRole('Founder');

        // 将 2号 用户指派为管理员
        $user_two = User::find(2);
        $user_two->assignRole('Maintainer');
    }
}
