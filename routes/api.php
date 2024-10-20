<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);

Route::group([
    "middleware" => ["auth:api"]
], function () {
    Route::post('logout', [ApiController::class, 'logout']);
});

Route::middleware('auth:api')->group(function(){

    Route::post('books',[BookController::class, 'store']);
    Route::get('books',[BookController::class, 'read']);
    Route::put('books/{id}',[BookController::class, 'update']);
    Route::delete('books/{id}',[BookController::class, 'destroy']);
});

Route::get('dashboard',[BookController::class, 'dashboard']);