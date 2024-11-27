<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
    });
});
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/animals', [AnimalController::class,'store']);
    Route::delete('/animals/{id}',[AnimalController::class,'deleteAnimal']);
    Route::put('/animals/{id}',[AnimalController::class,'updateAnimal']);
});

Route::get('/animals',[AnimalController::class,'getAllAnimals']);
Route::get('/animals/{id}',[AnimalController::class,'getAnimal']);

