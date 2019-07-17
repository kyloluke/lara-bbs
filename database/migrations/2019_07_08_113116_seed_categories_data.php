<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Category;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            [
                'name'        => 'javascript',
                'description' => '',
            ],
            [
                'name'        => 'node',
                'description' => '',
            ],
            [
                'name'        => 'wechat',
                'description' => '',
            ],
            [
                'name'        => 'php',
                'description' => '',
            ],
            [
                'name'        => 'linux',
                'description' => '',
            ],
            [
                'name'        => 'laravel',
                'description' => '',
            ],
            [
                'name'        => 'mysql',
                'description' => '',
            ],
            [
                'name'        => 'vue',
                'description' => '',
            ],
        ];

        // 批量插入数据只能使用  这里的 insert 方法
        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->truncate();
    }
}
