<?php

namespace App\Jobs;

use App\Handlers\SlugTranslateHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Topic;

class TranslateSlugJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $slug = (new SlugTranslateHandler($this->topic->title))->translate();

        // 为了避免陷入死循环这里避免使用 orm 使用 DB 类直接操作数据库
        \DB::table('topics')->where('id', $this->topic->id)->update(['slug' => $slug]);
    }
}
