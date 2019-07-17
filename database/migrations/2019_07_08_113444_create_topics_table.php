<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->index()->comment('标题');
            $table->text('body')->comment('内容');
            $table->unsignedBigInteger('user_id')->comment('关联用户');
            $table->unsignedBigInteger('category_id')->comment('关联分类');
            $table->unsignedInteger('reply_count')->default(0)->comment('回复总数');
            $table->unsignedInteger('view_count')->default(0)->comment('查看总数');
            $table->unsignedBigInteger('last_reply_user_id')->default(0)->comment('最后回复的用户的id');
            $table->integer('order')->default(0)->comment('排序');
            $table->text('excerpt')->nullable()->comment('文章摘要seo优化使用');
            $table->string('slug')->nullable()->comment('seo友好的url');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
