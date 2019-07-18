<?php

$api = app('Dingo\Api\Routing\Router');

// $api->version('v2', function ($api) {
//     $api->get('version', function () {
//         return response('this is version v2');
//     });
// });

$api->version('v1', ['namespace' => 'App\Http\Controllers\Api'], function ($api) {
    $api->post('verification', 'VerificationCodesController@store')->name('api.verificationCodes.store');
    $api->post('users', 'UsersController@store')->name('api.users.store');
});
