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
    Route::post('deploy', 'DebugController@deploy');
    Route::post('logs/js', 'DebugController@deploy');

    Route::apiResource('rating/games', 'Rating\GameController')->only(['index', 'show']);
    Route::get('rating/game-history/{history}', 'Rating\GameHistoryController@show');
    Route::apiResource('rating', 'Rating\ChannelController')->only(['index', 'show']);

    Route::get('games/top', 'GameController@top');
    Route::apiResource('games', 'GameController')->only(['index', 'show']);
    Route::post('games/offer', 'GameController@offer');


    Route::get('campaigns/all/tasks', 'AdvCampaigns\AdvTaskController@all');
    Route::get('campaigns/all/tasks/types', 'AdvCampaigns\AdvTaskController@types');
    Route::post('campaigns/{campaign}/logo', 'AdvCampaignController@updateLogo');
    Route::apiResource('campaigns', 'AdvCampaignController');
    Route::apiResource('campaigns.tasks', 'AdvCampaigns\AdvTaskController');

    Route::apiResource('inquires', 'InquireController');

    Route::get('users/me', 'UserController@me');
    Route::get('users/me/account', 'UserController@account');
    Route::get('users/me/kyc', 'UserController@kyc');
    Route::post('users/me/avatar', 'UserController@updateAvatar');
    Route::post('users/me/overlay', 'UserController@updateOverlay');
    Route::patch('users/me/set-role', 'UserController@setRole');
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


    Route::get('channels/top-donated', 'ChannelController@topDonated');
    Route::get('channels/top', 'ChannelController@top');
    Route::get('channels/{slug}/streams', 'ChannelController@streams');
    Route::apiResource('channels', 'ChannelController');

    Route::get('transactions/statuses', 'TransactionController@statuses');
    Route::get('transactions/types', 'TransactionController@types');
    //  Route::apiResource('transactions', 'TransactionController')->only(['store']);

    Route::get('streams/top', 'StreamController@top');
    Route::get('streams/closest', 'StreamController@closest');
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

    Route::get('/payments/{gateway}/{user}/{task}/checkout', array('as' => 'payment.checkout.create', 'uses' => 'PayController@checkout'));
    Route::get('/payments/{gateway}/{user}/{task}/completed', array('as' => 'payment.checkout.completed','uses' => 'PayController@completed'));
    Route::get('/payments/{gateway}/{user}/{task}/cancelled', array('as' => 'payment.checkout.cancelled','uses' => 'PayController@cancelled'));

    Route::post('/kycs/verify/{code}', 'KycController@verify');
    Route::post('/kycs/send-sms-code', 'KycController@sendSmsCode');
    Route::post('/kycs', 'KycController@store');

    Route::post('/withdraws/verify/{code}', 'WithdrawController@verify');
    Route::post('/withdraws', 'WithdrawController@store');

    /*Route::get('/stripe/purchase', 'StripeController@purchase');
    Route::get('/stripe/success', 'StripeController@success');
    Route::get('/stripe/fail', 'StripeController@fail');*/
});