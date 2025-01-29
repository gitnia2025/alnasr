<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\NumController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\FeedbackController;

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

// numbers Routes
Route::apiResource('numbers', NumController::class);
// Lab Routes
Route::apiResource('labs', LabController::class);
// feedback Routes
Route::apiResource('feedback', FeedbackController::class);