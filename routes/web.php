<?php

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::redirect('/', '/docs', 301);
