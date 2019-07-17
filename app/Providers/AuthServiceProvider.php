<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Laravel 5.8 新增功能 —— 自动授权注册
         * 参考 https://learnku.com/docs/laravel/5.8/authorization/3908#registering-policies
        */

        $this->registerPolicies();
        // 修改策略自动发现的逻辑
        Gate::guessPolicyNamesUsing(function($modelClass) {
            // 动态返回模型对应的策略名称，如： 'App\Models\User' => 'App\Policies\UserPolicy'
            // class_basename() 函数返回被删除了命名空间的指定类的类名
            return 'App\Policies\\'.class_basename($modelClass).'Policy';
        });

        // horizon 仪表盘路由权限
        \Horizon::auth(function($request) {
            // 是否是站长
            return \Auth::user()->hasRole('Founder');
        });
    }
}
