<?php 
use App\Http\Controllers\TalonlarController;



Route::post("talonlar",[TalonlarController::class,'getAll']);
Route::post("talonlar/add",[TalonlarController::class,'add']);
Route::post("talonlar/edit",[TalonlarController::class,'edit']);
Route::post("talonlar/delete",[TalonlarController::class,'delete']);

?>