<?php 
use App\Http\Controllers\LoginController;

Route::post('user/add',[LoginController::class,'save']);
Route::post('user/edit',[LoginController::class,'edit']);
Route::post('user/delete',[LoginController::class,'delete']);
Route::get("user",[LoginController::class,'checkUser'])->name('checkUser');
Route::post("users",[LoginController::class,'AllUser']);
?>