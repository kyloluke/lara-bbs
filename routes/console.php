<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


//Artisan::command('send-news', function () {
//    broadcast(new App\Events\News(date('Y-m-d H:i:s') . '开始广播的发送'));
//    $this->comment('发送成功');
//})->describe('Send News');

