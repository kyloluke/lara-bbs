<?php

namespace App\Providers;

use App\Observers\ReplyObserver;
use App\Observers\TopicObserver;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Reply;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('zh');

        view()->composer('layouts.app', function ($view) {
            return $view->with(['categories' => Category::all()]);
        });

        Topic::observe(TopicObserver::class);
        Reply::observe(ReplyObserver::class);
    }
}
