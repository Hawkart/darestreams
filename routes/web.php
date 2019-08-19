<?php

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::post('deploy', 'Api\DeployController@deploy');

Route::get('/', function () {
    return view('index');
});
