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

Route::post('/login', 'LoginController@login');
Route::post('/register-user', 'Auth\LoginController@registerUser');

Route::resource('user', 'UserController');
Route::resource('post', 'PostController');
Route::resource('comment', 'CommentController');

Route::get('/admin', 'AdminController@showDashboard');
Route::get('/backup/{tableName}', 'AdminController@dbBackUp');
Route::get('/manageusers/{page?}/{query?}/{searchString?}', 'AdminController@showUsers');
Route::get('/queryposts/{page?}/{query?}/{searchString?}', 'AdminController@showPosts');
Route::get('/backup', 'AdminController@showBackup');
Route::post('/restore-backup', 'AdminController@restoreBackup');

Route::post('/query/{tableName}', 'AdminController@customQuery');

Route::post('/vote/{id}', 'PostController@vote');

Route::get('/profile', 'UserController@showEditUserData');
Route::get('/adminuseredit/{id}', 'UserController@showAdminEditUserData');
Route::post('/edit-user-data/{id?}/{isAdminPanel?}', 'UserController@updateUserData');

Route::post('/search-posts', 'HomeController@searchPosts');
Route::get('/home/{page?}/{query?}/{searchString?}', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/{page?}/{query?}/{searchString?}', 'HomeController@index');
