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

Route::group(['middleware' => 'auth'], function () {
    // after login
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    // for user
    Route::resource('users', 'UsersController')->middleware(['can:user-cant']);
    Route::post('/deleteUser', 'AjaxController@deleteUser');
    Route::post('/user/{id}/', 'UsersController@editUser')->name('editUser');
    Route::any('/profile', 'UsersController@profile')->name("profile");
    Route::any('/setting', 'UsersController@setting')->name("setting")->middleware(['can:user-cant']);;
    Route::any('/member', 'TimesheetsController@member')->name("member");
    Route::post('/approve', 'AjaxController@approve');
    Route::post('/timesheet/{id}/', 'TimesheetsController@editTimesheet')->name('editTimesheet');

    // for timesheet
    Route::resource('timesheets', 'TimesheetsController')->middleware(['can:admin-cant']);
});

//Route::get('/', 'DashboardController@index');


Auth::routes();
Route::get('/logout', 'DashboardController@logout')->name('logout');
