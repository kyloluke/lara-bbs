<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('avatar')->nullable()->comment('头像url');
            $table->string('introduction')->nullable()->comment('个人简介');
            $table->string('phone')->nullable()->unique()->comment('手机号');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedInteger('notification_count')->default(0)->comment('未读通知数量');
            $table->rememberToken();
            $table->timestamp('last_actived_at')->nullable()->comment('最后登录时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
