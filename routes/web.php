<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/device/{id}', function ($id = 'dummy_id') {
    return view('device');
});

Route::get('/reg', function () {
    return view('device_registration');
});

Route::get('/multiple', function () {
    return view('device_multiple');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/', function () {
    return view('dashboard');
});
