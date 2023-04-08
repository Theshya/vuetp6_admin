<?php

use think\facade\Route;


Route::group(function () {
    Route::group('Test', function () {
        Route::get('test', 'Test/test');
        Route::post('login', 'Test/login');
        Route::post('upload', 'Test/upload');
    });
    
});