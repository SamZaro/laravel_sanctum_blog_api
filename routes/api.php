<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);



// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('post', PostController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
