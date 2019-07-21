<?php

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');
