<?php

//header('Access-Control-Allow-Credentials: true');

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

    Route::apiResource('rating', 'Rating\ChannelController')->only(['index', 'show']);

    Route::get('games/top', 'GameController@top');
    Route::apiResource('games', 'GameController')->only(['index', 'show']);
    Route::post('games/offer', 'GameController@offer');

    Route::apiResource('adv/campaigns', 'AdvCampaignController')->only(['index', 'store', 'update']);


    Route::get('users/me', 'UserController@me');
    Route::get('users/me/account', 'UserController@account');
    Route::post('users/me/avatar', 'UserController@updateAvatar');
    Route::post('users/me/overlay', 'UserController@updateOverlay');
    Route::get('users/me/campaigns', 'UserController@campaigns');
    //Route::patch('users/me/password', 'UserController@updatePassword');
    Route::get('users/me/get-donates-by-date', 'UserController@getDonateGroupDates');
    Route::get('users/me/get-donates-by-date/{date}/{stream}', 'UserController@getDonateGroupDatesByDateStream');
    Route::get('users/me/get-donates-by-date/{date}', 'UserController@getDonateGroupDatesByDate');
    Route::get('users/me/get-debit-withdraw-by-date', 'UserController@getDebitWithdrawGroupDates');
    Route::get('users/me/get-debit-withdraw-by-date/{date}', 'UserController@getDebitWithdrawGroupDatesByDate');
    Route::get('users/top', 'UserController@top');
    //Route::post('users/{user}/donate', 'UserController@donate');

    Route::apiResource('users', 'UserController')->only(['index', 'show', 'update']);
    Route::get('users/{user}/channel', 'UserController@channel');
    Route::apiResource('users.oauthproviders', 'Users\OAuthProviderController')->only(['index', 'show']);

    //Followers
    Route::post('users/{user}/follow', 'UserController@follow');
    Route::patch('users/{user}/unfollow', 'UserController@unfollow');
    Route::get('users/{user}/followers', 'UserController@followers');
    Route::get('users/{user}/followings', 'UserController@followings');
    Route::get('users/{user}/is-following', 'UserController@isFollowing');

    //Notifications
    Route::get('users/me/notifications/unread', 'Users\NotificationController@unread');
    Route::patch('users/me/notifications/set-read-all', 'Users\NotificationController@setReadAll');
    Route::patch('users/me/notifications/{notification}/set-read', 'Users\NotificationController@setRead');
    Route::apiResource('users.notifications', 'Users\NotificationController');

    Route::get('channels/top', 'ChannelController@top');
    Route::get('channels/{slug}/streams', 'ChannelController@streams');
    Route::apiResource('channels', 'ChannelController');

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

    //'PayPal_Rest'
    Route::get('/payments/{gateway}/{user}/{task}/checkout', array('as' => 'payment.checkout.create', 'uses' => 'PaymentController@checkout'));
    Route::get('/payments/{gateway}/{user}/{task}/completed', array('as' => 'payment.checkout.completed','uses' => 'PaymentController@completed'));
    Route::get('/payments/{gateway}/{user}/{task}/cancelled', array('as' => 'payment.checkout.cancelled','uses' => 'PaymentController@cancelled'));
});
