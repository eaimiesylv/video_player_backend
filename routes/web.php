<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->noContent();
});

/* Application gateway health check */
Route::get('/health', function () {
    return "ok";
});
Route::get('/testpaypal', function () {

    return view("testpaypal");
});
Route::get('/paypal', function () {

    return view("paypal");
});
