<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/wechat', 'WeChatController@serve');
Route::get('/users', 'UserController@users');
//Route::get('/user/{openId}', 'UserController@user');
Route::get('/materials', 'MaterialController@materials');
Route::get('/material/{openId}', 'MaterialController@material');
Route::get('/menu/add', 'MenuController@menu');
Route::get('/menu/all', 'MenuController@menus');
Route::get('/menu/delete', 'MenuController@delete');
Route::get('/oauth_callback', 'WeChatController@test1');
Route::get('/user/profile', 'WeChatController@test2');
