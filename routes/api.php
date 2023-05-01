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
    
    require __DIR__."/asliddin.php";
    require __DIR__."/sheroz.php";
    require __DIR__."/axror.php";

});
Route::any('/bot','BotController@index');
Route::any('/asl/bot','AslController@index');

Route::post("logout",[LoginController::class,'logout']);

Route::post("login",[LoginController::class,'index'])->name('login');