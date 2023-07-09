<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
| Route::get('/', function () {
|     return view('welcome');
| });
*/

Route::get  ("/account", "App\\Http\\Controllers\\AccountController@get");
Route::post ("/account", "App\\Http\\Controllers\\AccountController@post");
Route::get  ("/information", "App\\Http\\Controllers\\InformationController@get");
Route::post ("/information", "App\\Http\\Controllers\\InformationController@post");
Route::get  ("/reset-password", "App\\Http\\Controllers\\ResetPasswordController@get");
Route::post ("/reset-password", "App\\Http\\Controllers\\ResetPasswordController@post");
