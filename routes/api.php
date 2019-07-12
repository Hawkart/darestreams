<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([/*'middleware' => 'auth', */'namespace' => 'Api'], function () {
    Route::apiResource('games', 'GameController')->only(['index', 'show']);

    Route::get('users/me', 'UserController@me');
    Route::apiResource('users', 'UserController')->only(['index', 'show']);
    Route::apiResource('users.transactions', 'Users\TransactionController');
    //Route::apiResource('users.notifications', 'Users\NotificationController');

    Route::apiResource('streams', 'StreamController');
    Route::apiResource('streams.tasks', 'Streams\TaskController');
    Route::apiResource('streams.tasks.votes', 'Streams\Tasks\VoteController');
    Route::apiResource('streams.tasks.transactions', 'Streams\Tasks\TransactionController');

    Route::apiResource('streams.messages', 'Streams\MessageController');
    //Route::apiResource('streams.participant', 'Streams\ParticipantController');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    //Route::patch('settings/profile', 'Settings\ProfileController@update');
    //Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => 'guest:api', 'namespace' => 'Auth'], function () {
    Route::post('login', 'LoginController@login');
    Route::post('register', 'RegisterController@register');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset');
    Route::post('email/verify/{user}', 'VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'VerificationController@resend');
    Route::post('oauth/{driver}', 'OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');
});
