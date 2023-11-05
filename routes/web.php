<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [UserController::class, 'loginIndex']);
Route::get('/login', [UserController::class, 'loginIndex']);
Route::post('/login/login', [UserController::class, 'login']);

Route::get('/signUp', [UserController::class, 'signUpIndex']);
Route::post('/signUp/getBindStore', [UserController::class, 'getBindStore']);
Route::post('/signUp/signUp', [UserController::class, 'signUp']);

Route::get('/logout', [UserController::class, 'logout'])->middleware('userAuthenticate');

Route::get('/home', [HomeController::class, 'index'])->middleware('userAuthenticate');
Route::post('/home/getUserContent', [HomeController::class, 'getUserContent'])->middleware('userAuthenticate');
Route::post('/home/updateStoreInfo', [HomeController::class, 'updateStoreInfo'])->middleware('userAuthenticate');

Route::get('/comment', [CommentController::class, 'index'])->middleware('userAuthenticate');
Route::post('/comment/getComment', [CommentController::class, 'getComment'])->middleware('userAuthenticate');
Route::post('/comment/getCommentInfo', [CommentController::class, 'getCommentInfo'])->middleware('userAuthenticate');