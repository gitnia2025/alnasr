<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\OfferController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// // Example route to get the authenticated user
// Route::middleware('api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Specialist Routes
Route::apiResource('specialists', SpecialistController::class);

// Doc Routes
Route::apiResource('docs', DocController::class);

// Offer Routes
Route::apiResource('offers', OfferController::class);