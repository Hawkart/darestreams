<?php

header('Access-Control-Allow-Credentials: true');

//Auth
Route::group(['namespace' => 'Auth'], function () {

    Route::group(['middleware' => 'guest:api'], function () {
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'ResetPasswordController@reset');
        Route::post('email/verify/{user}', 'VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'VerificationController@resend');
        Route::post('oauth/{driver}', 'OAuthController@redirectToProvider');
        Route::get('oauth/{driver}/callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'LoginController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    //Route::patch('settings/profile', 'Settings\ProfileController@update');
    //Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['namespace' => 'Api'], function () {
    Route::apiResource('games', 'GameController')->only(['index', 'show']);

    Route::get('users/me', 'UserController@me');
    Route::apiResource('users', 'UserController')->only(['index', 'show']);
    Route::apiResource('users.transactions', 'Users\TransactionController');
    Route::apiResource('users.notifications', 'Users\NotificationController');

    Route::apiResource('streams', 'StreamController');
    Route::apiResource('streams.tasks', 'Streams\TaskController');
    Route::apiResource('streams.tasks.votes', 'Streams\Tasks\VoteController');
    Route::apiResource('streams.tasks.transactions', 'Streams\Tasks\TransactionController');

    Route::apiResource('streams.messages', 'Streams\MessageController');
    //Route::apiResource('streams.participant', 'Streams\ParticipantController');
});
