<?php 
use App\Http\Controllers\TalonlarController;



Route::post("talonlar",[TalonlarController::class,'getAll']);
Route::post("talonlar/add",[TalonlarController::class,'add']);

?>