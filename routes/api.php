<?php

header('Access-Control-Allow-Credentials: true');

//Auth
Route::group(['namespace' => 'Auth'], function () {

    Route::group(['middleware' => 'guest:api'], function () {
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'ResetPasswordController@reset');
        Route::get('oauth/{driver}', 'OAuthController@redirectToProvider');
        Route::get('oauth/{driver}/callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');
    });

    Route::post('email/verify/{user}', 'VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'VerificationController@resend');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'LoginController@logout');
    });
});

Route::group(['namespace' => 'Api'], function () {

    Route::get('users/{user}/login', 'UserController@fakeLogin');

    Route::post('deploy', 'DeployController@deploy');

    Route::get('games/top', 'GameController@top');
    Route::apiResource('games', 'GameController')->only(['index', 'show']);
    Route::post('games/offer', 'GameController@offer');


    Route::get('users/me', 'UserController@me');
    Route::get('users/me/get-donates-by-date', 'UserController@getDonatesByDate');
    Route::get('users/me/get-debit-withdraw-by-date', 'UserController@getDebitWithdrawByDate');

    Route::get('users/top', 'UserController@top');
    //Route::post('users/{user}/donate', 'UserController@donate');

    Route::apiResource('users', 'UserController')->only(['index', 'show', 'update']);
    Route::get('users/{user}/account', 'UserController@account');
    Route::get('users/{user}/channel', 'UserController@channel');
    Route::post('users/{user}/avatar', 'UserController@updateAvatar');
    Route::post('users/{user}/overlay', 'UserController@updateOverlay');
    Route::patch('users/{user}/password', 'UserController@updatePassword');
    Route::apiResource('users.oauthproviders', 'Users\OAuthProviderController')->only(['index', 'show']);

    //Followers
    Route::post('users/{user}/follow', 'UserController@follow');
    Route::patch('users/{user}/unfollow', 'UserController@unfollow');
    Route::get('users/{user}/followers', 'UserController@followers');
    Route::get('users/{user}/followings', 'UserController@followings');

    //Notifications
    Route::get('users/{user}/notifications/unread', 'Users\NotificationController@unread');
    Route::patch('users/{user}/notifications/set-read-all', 'Users\NotificationController@setReadAll');
    Route::apiResource('users.notifications', 'Users\NotificationController');
    Route::patch('users/{user}/notifications/{notification}/set-read', 'Users\NotificationController@setRead');

    Route::get('channels/top', 'ChannelController@top');
    Route::apiResource('channels', 'ChannelController')->only(['index', 'show', 'update']);

    Route::get('transactions/statuses', 'TransactionController@statuses');
    Route::get('transactions/types', 'TransactionController@types');
    //  Route::apiResource('transactions', 'TransactionController')->only(['store']);

    Route::get('streams/top', 'StreamController@top');
    Route::get('streams/statuses', 'StreamController@statuses');
    Route::apiResource('streams', 'StreamController');
    Route::get('streams/{stream}/thread', 'StreamController@thread');

    Route::post('tasks/{task}/donate', 'TaskController@donate');
    Route::patch('tasks/{task}/set-vote', 'TaskController@setVote');
    Route::get('tasks/statuses', 'TaskController@statuses');
    Route::apiResource('tasks', 'TaskController');

    Route::apiResource('threads', 'ThreadController')->only(['index', 'show']);
    Route::apiResource('threads.participants', 'Threads\ParticipantController')->only(['index']);
    Route::apiResource('threads.messages', 'Threads\MessageController');

    Route::apiResource('votes', 'VoteController')->only(['index', 'show', 'update']);

    //'PayPal_Rest'
    Route::get('/payments/{gateway}/{user}/{task}/checkout', array('as' => 'payment.checkout.create', 'uses' => 'PaymentController@checkout'));
    Route::get('/payments/{gateway}/{user}/{task}/completed', array('as' => 'payment.checkout.completed','uses' => 'PaymentController@completed'));
    Route::get('/payments/{gateway}/{user}/{task}/cancelled', array('as' => 'payment.checkout.cancelled','uses' => 'PaymentController@cancelled'));
});
