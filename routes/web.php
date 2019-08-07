<?php

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::redirect('/', '/docs', 301);

/*Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');*/
