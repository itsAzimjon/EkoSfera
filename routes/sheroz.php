<?php 
use App\Http\Controllers\RegionController;
use App\Http\Controllers\OrganizationController;

Route::group(['prefix' => 'organization'], function () {
    Route::post('/get', [OrganizationController::class, 'get']);
    Route::post('/add', [OrganizationController::class, 'add']);
    Route::post('/edit', [OrganizationController::class, 'edit']);
});

Route::post('region/get', [RegionController::class, 'get']);
?>