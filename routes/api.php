<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {


    Route::post('/login', [App\Http\Controllers\Users\AuthController::class, 'login'])->name('login');
    Route::post('/register', [App\Http\Controllers\Users\UserController::class, 'store']);


});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->group(function () {

        Route::post('/log-out', [App\Http\Controllers\Users\AuthController::class, 'logout'])->name('login-out');
        Route::resource('categories', App\Http\Controllers\Videos\CategoryController::class);
        Route::resource('videos', App\Http\Controllers\Videos\VideoController::class);


    });
});
