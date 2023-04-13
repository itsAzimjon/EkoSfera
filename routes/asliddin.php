<?php 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShartnomalarController;

Route::post('user/add',[LoginController::class,'save']);
Route::post('user/edit',[LoginController::class,'edit']);
Route::post('user/delete',[LoginController::class,'delete']);
Route::get("user",[LoginController::class,'checkUser'])->name('checkUser');
Route::post("users",[LoginController::class,'AllUser']);

Route::post("shartnomalar",[ShartnomalarController::class,'getAll']);
Route::post("shartnomalar/add",[ShartnomalarController::class,'add']);
Route::post("shartnomalar/edit",[ShartnomalarController::class,'edit']);
Route::post("shartnomalar/delete",[ShartnomalarController::class,'delete']);
Route::get("tumanlar",[ShartnomalarController::class,'tumanlar']);

?>