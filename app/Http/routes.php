<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::post('/api/v1/user', 'UserController@store');
Route::post('/api/v1/user/connect', 'UserController@connect');

Route::group(array('prefix' => 'api/v1', 'middleware' => 'auth.basic.once'), function()
{
    Route::resource('story', 'StoryController', ['only' => ['store', 'show', 'update', 'destroy']]);
    Route::resource('user', 'UserController', ['only' => ['show', 'update', 'destroy']]);
    Route::resource('page', 'PageController', ['only' => ['show', 'update', 'destroy']]);
    Route::post('story/{storyId}/page', 'PageController@store');

});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
