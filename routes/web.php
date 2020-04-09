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

Route::group([], function () {

    // for user
    Route::resource('users', 'UsersController');
    Route::post('/deleteUser', 'AjaxController@deleteUser');
    Route::post('/user/{id}/', 'UsersController@editUser')->name('editUser');
    Route::any('/profile', 'UsersController@profile')->name("profile");
    Route::any('/setting', 'UsersController@setting')->name("setting");

    // for timesheet
    Route::resource('timesheets', 'TimesheetsController');
});

Route::get('/', function () {
    return view('dashboard.index');
});
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

//Route::get('/', 'DashboardController@index');


Auth::routes();
Route::get('/logout', 'DashboardController@logout')->name('logout');
