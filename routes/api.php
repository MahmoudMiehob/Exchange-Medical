<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\API\InstructionController;
use App\Http\Controllers\Api\JobApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
});


// Job routes
Route::prefix('jobs')->group(function () {
    Route::get('/', [JobController::class, 'index']);
    Route::post('/', [JobController::class, 'store']);
    Route::get('/{id}', [JobController::class, 'show']);
    Route::post('/{id}', [JobController::class, 'update']);
    Route::delete('/{id}', [JobController::class, 'destroy']);
});

// instructions
    Route::apiResource('instructions', InstructionController::class);

//contact
    Route::apiResource('contacts', ContactController::class);

//jobsApplication
    Route::post('/job-applications/apply', [JobApplicationController::class, 'apply']);
    Route::get('/job-applications', [JobApplicationController::class, 'index']);
    Route::get('/job-applications/{job_offer_id}', [JobApplicationController::class, 'showApplicationsByJob']);


    
    Route::apiResource('medicines', MedicineController::class);

    // Device Routes
    Route::apiResource('devices', DeviceController::class);
    
    // Donation Routes (to show all donations)
    Route::get('donations', [DonationController::class, 'index']);
    
    // Order Routes
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders', [OrderController::class, 'index']); 
    Route::get('users/{user}/orders', [OrderController::class, 'userOrders']);