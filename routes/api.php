<?php

use App\Http\Controllers\AnimalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/animals', [AnimalController::class,'store']);
Route::get('/animals',[AnimalController::class,'getAllAnimals']);
Route::get('/animals/{id}',[AnimalController::class,'getAnimal']);
Route::delete('/animals/{id}',[AnimalController::class,'deleteAnimal']);
