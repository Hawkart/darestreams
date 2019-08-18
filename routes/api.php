<?php

header('Access-Control-Allow-Credentials: true');

//Auth
Route::group(['namespace' => 'Auth'], function () {

    Route::group(['middleware' => 'guest:api'], function () {
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'ResetPasswordController@reset');
        Route::post('oauth/{driver}', 'OAuthController@redirectToProvider');
        Route::get('oauth/{driver}/callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');
    });

    Route::post('email/verify/{user}', 'VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'VerificationController@resend');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'LoginController@logout');
    });
});

Route::group(['namespace' => 'Api'], function () {

    Route::post('deploy', 'DeployController@deploy');

    Route::get('games/top', 'GameController@top');
    Route::apiResource('games', 'GameController')->only(['index', 'show']);
    Route::post('games/offer', 'GameController@offer');

    Route::get('users/me', 'UserController@me');
    Route::apiResource('users', 'UserController')->only(['index', 'show', 'update']);
    Route::get('users/{user}/account', 'UserController@account');
    Route::get('users/{user}/channel', 'UserController@channel');
    Route::patch('users/{user}/avatar', 'UserController@updateAvatar');
    Route::patch('users/{user}/overlay', 'UserController@updateOverlay');
    Route::patch('users/{user}/password', 'UserController@updatePassword');
    Route::apiResource('users.transactions', 'Users\TransactionController')->only(['index', 'show']);
    Route::apiResource('users.oauthproviders', 'Users\OAuthProviderController')->only(['index', 'show']);

    //Followers
    Route::post('users/{user}/follow', 'UserController@follow');
    Route::patch('users/{user}/unfollow', 'UserController@unfollow');
    Route::get('users/{user}/followers', 'UserController@followers');
    Route::get('users/{user}/followings', 'UserController@followings');

    //Notifications
    Route::get('users/{user}/notifications/unread', 'Users\NotificationController@unread');
    Route::patch('users/{user}/notifications/setReadAll', 'Users\NotificationController@setReadAll');
    Route::apiResource('users.notifications', 'Users\NotificationController');
    Route::patch('users/{user}/notifications/{notification}/setRead', 'Users\NotificationController@setRead');

    Route::get('channels/top', 'ChannelController@top');
    Route::apiResource('channels', 'ChannelController')->only(['index', 'show', 'update']);

    Route::apiResource('streams', 'StreamController');
    Route::get('streams/{stream}/thread', 'StreamController@thread');
    Route::apiResource('streams.tasks', 'Streams\TaskController');
    Route::apiResource('streams.tasks.votes', 'Streams\Tasks\VoteController');
    Route::apiResource('streams.tasks.transactions', 'Streams\Tasks\TransactionController');

    Route::apiResource('threads', 'ThreadController')->only(['index', 'show']);
    Route::apiResource('threads.participants', 'Threads\ParticipantController')->only(['index']);
    Route::apiResource('threads.messages', 'Threads\MessageController');

    Route::apiResource('votes', 'VoteController')->only(['index', 'show', 'update']);
});
