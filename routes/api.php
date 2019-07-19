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
                $api->post('verification', 'VerificationCodesController@store')->name('api.verificationCodes.store');
                $api->post('users', 'UsersController@store')->name('api.users.store');
            }
        );
    }
);
