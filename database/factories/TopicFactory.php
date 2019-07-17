<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Topic;
use Faker\Generator as Faker;

$factory->define(Topic::class, function (Faker $faker) {
    $updated_at = $faker->dateTimeThisMonth();
    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'title' => $faker->sentence,
        'body' => join("\n\n", $faker->paragraphs(mt_rand(3, 6))),
        'excerpt' => $faker->sentence,
        'created_at' => $created_at,
        'updated_at' => $updated_at
    ];
});
