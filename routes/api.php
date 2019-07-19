<?php

$api = app('Dingo\Api\Routing\Router');

// $api->version('v2', function ($api) {
//     $api->get('version', function () {
//         return response('this is version v2');
//     });
// });

$api->version(
    'v1',
    ['namespace' => 'App\Http\Controllers\Api'],
    function ($api) {
        $api->group(
            [
                'middleware' => 'api.throttle',
                'limit' => config('api.rate_limits.sign.limit'),
                'expired' => config('api.rate_limits.sign.expires')
            ],
            function ($api) {
                // 获取手机验证码
                $api->post('verificationCodes', 'VerificationCodesController@store')->name('api.verificationCodes.store');
                // 携带手机验证码 注册用户
                $api->post('users', 'UsersController@store')->name('api.users.store');
                // 获取图片验证码
                $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');
                
            }
        );
    }
);
