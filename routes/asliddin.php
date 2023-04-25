<?php 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShartnomalarController;
use App\Http\Controllers\BuyurtmaController;

Route::post('user/add',[LoginController::class,'save']);
Route::post('user/edit',[LoginController::class,'edit']);
Route::post('user/delete',[LoginController::class,'delete']);
Route::get("user",[LoginController::class,'checkUser'])->name('checkUser');
Route::post("users",[LoginController::class,'AllUser']);
Route::get("organization",[LoginController::class,'organization']);

Route::post("shartnomalar",[ShartnomalarController::class,'getAll']);
Route::get("shartnomalar/get",[ShartnomalarController::class,'get']);
Route::post("shartnomalar/add",[ShartnomalarController::class,'add']);
Route::post("shartnomalar/edit",[ShartnomalarController::class,'edit']);
Route::post("shartnomalar/delete",[ShartnomalarController::class,'delete']);
Route::get("tumanlar",[ShartnomalarController::class,'tumanlar']);

Route::get("buyurtma/get",[BuyurtmaController::class,'get']);
Route::post("buyurtma",[BuyurtmaController::class,'getAll']);
Route::post('buyurtma/add',[BuyurtmaController::class,'add']);
Route::post('buyurtma/edit',[BuyurtmaController::class,'edit']);
Route::post('buyurtma/delete',[BuyurtmaController::class,'delete']);

?>