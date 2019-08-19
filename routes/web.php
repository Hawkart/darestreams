<?php

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::post('deploy', 'DeployController@deploy');


