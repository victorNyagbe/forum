<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'TopicController@index')->name('topics.index');

Route::resource('topics', 'TopicController')->except(['index']);

Route::post('comments/{topic}', 'CommentController@store')->name('comments.store');

Route::post('comments/{comment}/reply', 'CommentController@storeCommentReply')->name('comments.storeReply');

Route::post('topics/markedAsSolution/{topic}/{comment}', 'CommentController@markedAsSolution')->name('comments.markedAsSolution');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
