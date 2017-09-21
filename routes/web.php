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


Route::any('/', 'WeChatController@responseMsg');
Route::group(['middleware' => ['web']], function () {
    Route::any('/weixin', 'WeChatController@wechat');
    Route::group(['middleware' => ['wechat.oauth']], function () {
        Route::get('/user/profile', 'WeChatController@test2');
    });
    Route::get('/oauth_callback', 'WeChatController@test1');

});
