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

Route::group(['middleware' => ['auth', 'is_active']], function () {
    // after login
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // for user
    Route::group(['middleware'  => 'can:user-cant'], function() {
        Route::get('/users', 'UsersController@index')->name('users.index');
        Route::get('/users/create', 'UsersController@create')->name('users.create');
        Route::post('/users/store', 'UsersController@store')->name('users.store');
        Route::get('/users/{id}/edit', 'UsersController@edit')->name('users.edit');
        Route::post('/users/{id}/update', 'UsersController@update')->name('users.update');
        Route::get('/setting', 'UsersController@setting')->name('setting');
        Route::post('/setting', 'UsersController@updateSetting')->name('update_setting');
    });
    Route::get('/profile', 'UsersController@profile')->name("profile");
    Route::post('/updateProfile', 'UsersController@updateProfile')->name("update_profile");
    
    // for ajax
    Route::post('/users/delete', 'AjaxController@deleteUser');
    Route::post('/approve', 'AjaxController@approve');
    
    // for timesheet
    // Route::resource('timesheets', 'TimesheetsController')->middleware(['can:admin-cant']);
    Route::get('/timesheets', 'TimesheetsController@index')->name("timesheets.index");
    Route::get('/timesheets/create', 'TimesheetsController@create')->name("timesheets.create");
    Route::post('/timesheets/store', 'TimesheetsController@store')->name("timesheets.store");
    Route::get('/timesheets/{id}/show', 'TimesheetsController@show')->name("timesheets.show");
    Route::get('/timesheets/{id}/edit', 'TimesheetsController@edit')->name("timesheets.edit");
    Route::put('/timesheets/{id}/update', 'TimesheetsController@update')->name("timesheets.update");
    Route::get('/timesheet/search', 'TimesheetsController@search')->name("timesheets.search");
    Route::any('/member', 'TimesheetsController@getTimesheetsOfMembers')->name("member");
    Route::post('/timesheet/{id}/', 'TimesheetsController@editTimesheet')->name('editTimesheet');
});

Auth::routes();
Route::get('/logout', 'DashboardController@logout')->name('logout');
