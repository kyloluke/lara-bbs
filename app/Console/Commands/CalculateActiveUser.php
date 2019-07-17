<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CalculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     * 执行命令
     * @var string
     */
    protected $signature = 'larabbs:calculate-active-user';

    /**
     * The console command description.
     * 命令描述
     * @var string
     */
    protected $description = '生成活跃用户';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 最终执行的方法
     * @return mixed
     */
    public function handle(User $user)
    {
        $this->info("开始计算....");

        $user->calculateAndCacheActiveUsers();

        $this->info('生成成功');
    }
}
