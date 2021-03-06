<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware(['guest']);

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('user/activation/{token}', 'Auth\RegisterController@activateUser');

Route::group([
        'middleware' => [
            'auth', 'roles'
        ],
        'roles' => 'Admin'
], function() {
    Route::resource('/people', 'PeopleController');
});
