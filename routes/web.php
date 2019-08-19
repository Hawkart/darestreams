<?php

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
