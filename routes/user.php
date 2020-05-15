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

Route::group(['middleware' => ['auth', 'can:user']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('timesheets', 'TimesheetsController@index')->name('timesheets.index');
    Route::get('timesheets/create', 'TimesheetsController@create')->name('timesheets.create');
    Route::post('timesheets/store', 'TimesheetsController@store')->name('timesheets.store');
    Route::get('timesheets/{timesheet}/edit', 'TimesheetsController@edit')->where(['timesheet' => '[0-9]+'])->name('timesheets.edit');
    Route::post('timesheets/{timesheet}/update', 'TimesheetsController@update')->where(['timesheet' => '[0-9]+'])->name('timesheets.update');
    Route::get('timesheets/{timesheet}/show', 'TimesheetsController@show')->where(['timesheet' => '[0-9]+'])->name('timesheets.show');
    Route::get('timesheets/search', 'TimesheetsController@search')->name('timesheets.search');
    Route::get('timesheets/member', 'TimesheetsController@member')->name('timesheets.member');
    Route::get('profile', 'ProfileController@index')->name('profile');
    Route::post('profile/update', 'ProfileController@update')->name('profile.update');
    Route::post('approve', 'AjaxController@approve')->name('timesheets.approve');
});

Route::get('/login', 'AuthController@showLoginForm')->name('show.login');
Route::post('/login', 'AuthController@login')->name('login');
Route::get('/logout', 'AuthController@logout')->name('logout');

