<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:admin', 'can:admin']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('users/create', 'UsersController@create')->name('users.create');
    Route::post('users/store', 'UsersController@store')->name('users.store');
    Route::get('users/{user}/edit', 'UsersController@edit')->where(['user' => '[0-9]+'])->name('users.edit');
    Route::post('users/{user}/update', 'UsersController@update')->where(['user' => '[0-9]+'])->name('users.update');
    Route::post('users/{user}/delete', 'UsersController@delete')->name('users.delete');
    Route::get('setting', 'SettingController@index')->name('setting.index');
    Route::post('setting/{setting}/update', 'SettingController@update')->where(['setting' => '[0-9]+'])->name('setting.update');
});
    
Route::get('/login', 'AuthController@showLoginForm')->name('show.login');
Route::post('/login', 'AuthController@login')->name('login');
Route::get('/logout', 'AuthController@logout')->name('logout');

