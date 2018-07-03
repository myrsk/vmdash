<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
//Auth::routes();
// Authentication Routes...
Route::get('login', '\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', '\App\Http\Controllers\Auth\LoginController@login');
Route::post('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// Registration Routes...
//Route::get('register', '\App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', '\App\Http\Controllers\Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', '\App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', '\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', '\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset');
/*
|------------------------------------------------------------------------------------
| Admin
|------------------------------------------------------------------------------------
*/
Route::group(['prefix' => ADMIN, 'as' => ADMIN . '.', 'middleware'=>['auth', 'Role:0']], function() {
    //Route::get('/', 'DashboardController@index')->name('dash');
    Route::get('/', 'ServerController@index')->name('dash');
    Route::resource('users', 'UserController');
    Route::resource('servers', 'ServerController');
    Route::post('/ajax/sshcommands', 'ServerController@ajaxsshcommands');
    Route::post('/servers/softreboot', array('as' => 'serveraction.softReboot', 'uses' => 'ServerController@softReboot'));
    Route::post('/servers/hardreboot', array('as' => 'serveraction.hardReboot', 'uses' => 'ServerController@hardReboot'));
    Route::post('/servers/shutdown', array('as' => 'serveraction.shutdown', 'uses' => 'ServerController@shutdown'));
    Route::post('/servers/poweron', array('as' => 'serveraction.poweron', 'uses' => 'ServerController@poweron'));
    Route::post('/servers/poweroff', array('as' => 'serveraction.poweroff', 'uses' => 'ServerController@poweroff'));
    Route::post('/servers/enablerescue', array('as' => 'serveraction.enablerescue', 'uses' => 'ServerController@enablerescue'));
    Route::post('/servers/disablerescue', array('as' => 'serveraction.disablerescue', 'uses' => 'ServerController@disablerescue'));
    Route::post('/servers/rootpasswordreset', array('as' => 'serveraction.rootpasswordreset', 'uses' => 'ServerController@rootpasswordreset'));
    Route::post('/servers/reinstallos', array('as' => 'serveraction.reinstallos', 'uses' => 'ServerController@reinstallos'));
    Route::post('/servers/attachiso', array('as' => 'serveraction.attachiso', 'uses' => 'ServerController@attachiso'));
    Route::post('/servers/removeiso', array('as' => 'serveraction.removeiso', 'uses' => 'ServerController@removeiso'));
});

Route::get('/', function () {
    return view('auth.login');
});


