<?php 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShartnomalarController;
use App\Http\Controllers\BuyurtmaController;
use App\Http\Controllers\HaydovchiController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\TexnikaController;
use App\Http\Controllers\YolvaraqaaController;

Route::post('user/add',[LoginController::class,'save']);
Route::post('user/edit',[LoginController::class,'edit']);
Route::post('user/email',[LoginController::class,'email']);
Route::post('user/delete',[LoginController::class,'delete']);
Route::get("user",[LoginController::class,'checkUser'])->name('checkUser');
Route::post("users",[LoginController::class,'AllUser']);
Route::post("user/holat",[LoginController::class,'holat']);

Route::post("shartnomalar",[ShartnomalarController::class,'getAll']);
Route::get("shartnomalar/get",[ShartnomalarController::class,'get']);
Route::post("shartnomalar/add",[ShartnomalarController::class,'add']);
Route::post("shartnomalar/holat",[ShartnomalarController::class,'holat']);
Route::post("shartnomalar/edit",[ShartnomalarController::class,'edit']);
Route::post("shartnomalar/delete",[ShartnomalarController::class,'delete']);
Route::get("tumanlar",[ShartnomalarController::class,'tumanlar']);

Route::get("buyurtma/get",[BuyurtmaController::class,'get']);
Route::post("buyurtma",[BuyurtmaController::class,'getAll']);
Route::post('buyurtma/add',[BuyurtmaController::class,'add']);
Route::post('buyurtma/edit',[BuyurtmaController::class,'edit']);
Route::post('buyurtma/delete',[BuyurtmaController::class,'delete']);


Route::post("haydovchi",[HaydovchiController::class,'getAll']);
Route::get("haydovchi/get",[HaydovchiController::class,'get']);
Route::post('haydovchi/add',[HaydovchiController::class,'add']);
Route::post('haydovchi/edit',[HaydovchiController::class,'edit']);
Route::post('haydovchi/delete',[HaydovchiController::class,'delete']);


Route::post("texnika",[TexnikaController::class,'getAll']);
Route::get("texnika/get",[TexnikaController::class,'get']);
Route::post('texnika/add',[TexnikaController::class,'add']);
Route::post('texnika/edit',[TexnikaController::class,'edit']);
Route::post('texnika/delete',[TexnikaController::class,'delete']);

Route::get("organization",[LoginController::class,'organization']);
Route::post("organization/holat",[OrganizationController::class,'holat']);


Route::post("yolvaraqa",[YolvaraqaaController::class,'index']);
Route::post('yolvaraqa/add',[YolvaraqaaController::class,'add']);
Route::get('yolvaraqa/get',[YolvaraqaaController::class,'get']);
Route::post('yolvaraqa/edit',[YolvaraqaaController::class,'edit']);
Route::post('yolvaraqa/delete',[YolvaraqaaController::class,'delete']);
Route::post("yolvaraqa/holat",[YolvaraqaaController::class,'holat']);

?>