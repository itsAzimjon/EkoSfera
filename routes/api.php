<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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
Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('user/add',[LoginController::class,'save']);
    Route::post('user/edit',[LoginController::class,'edit']);
    Route::post('user/delete',[LoginController::class,'delete']);
    Route::get("user",[LoginController::class,'checkUser'])->name('checkUser');
    Route::post("users",[LoginController::class,'AllUser']);
    Route::post("users/count",[LoginController::class,'count']);

});
Route::post("logout",[LoginController::class,'logout']);

Route::post("login",[LoginController::class,'index'])->name('login');