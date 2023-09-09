<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('GetUsers'       ,[HomeController::class,'GetUser']);
Route::post('updateUser'    ,[HomeController::class,'updateUser']);
Route::post('StoreUsers'    ,[HomeController::class,'StoreUsers']);
Route::get('checkUser'      ,[HomeController::class,'checkUser']);
Route::post('DeleteUser'    ,[HomeController::class,'DeleteUser']);

Route::get('post'           ,[PostController::class,'index']);
Route::get('getPost'        ,[PostController::class,'getPost']);
Route::post('StorePost'     ,[PostController::class,'StorePost']);
Route::post('updatePost'     ,[PostController::class,'updatePost']);
Route::post('DeletePost'     ,[PostController::class,'DeletePost']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
