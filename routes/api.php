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

// instructions
Route::prefix('instructions')->group(function () {
    Route::get('/', [InstructionController::class, 'index']);
    Route::get('/{id}', [InstructionController::class, 'show']);
});

// contactus
Route::post('/contacts', [ContactController::class, 'store']);

//jobsApplication
Route::post('/job-applications/apply', [JobApplicationController::class, 'apply']);


// medicines
Route::prefix('medicines')->group(function () {
    Route::get('/', [MedicineController::class, 'index']);
    Route::get('/{id}', [MedicineController::class, 'show']);
});

// device
Route::prefix('devices')->group(function () {
    Route::get('/', [DeviceController::class, 'index']); // Show all
    Route::get('/{id}', [DeviceController::class, 'show']); // Show single
});

Route::middleware(['token.check'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    // admin routes
    Route::group(['middleware' => 'role:admin'], function () {
        // Job routes
        Route::prefix('jobs')->group(callback: function () {
            Route::post('/', [JobController::class, 'store']);
            Route::post('/{id}', [JobController::class, 'update']);
            Route::delete('/{id}', [JobController::class, 'destroy']);
        });

        // instructions
        Route::prefix('instructions')->group(function () {
            Route::post('/', [InstructionController::class, 'store']);
            Route::patch('/{id}', [InstructionController::class, 'update']);
            Route::delete('/{id}', [InstructionController::class, 'destroy']);
        });

        // contactus
            Route::get('contacts', [ContactController::class , 'index']);



        //jobsApplication   
        Route::get('/job-applications', [JobApplicationController::class, 'index']);
        Route::get('/job-applications/{job_offer_id}', [JobApplicationController::class, 'showApplicationsByJob']);


        Route::patch('/medicines/{id}', [MedicineController::class, 'update']);
        Route::patch('/devices/{id}', [DeviceController::class, 'update']);

        // Order
        Route::get('orders', [OrderController::class, 'index']);
    });




    // Donor routes
    Route::group(['middleware' => 'role:donor'], function () {
        Route::post('/medicines', [MedicineController::class, 'store']);

        Route::post('/devices', [DeviceController::class, 'store']);
    });




    // Needy routes
    Route::group(['middleware' => 'role:needy'], function () {
        // Job routes
        Route::prefix('jobs')->group(callback: function () {
            Route::get('/', [JobController::class, 'index']);
            Route::get('/{id}', [JobController::class, 'show']);
        });

        // Donation
        Route::get('donations', [DonationController::class, 'index']);

        // Order Routes
        Route::post('orders', [OrderController::class, 'store']);
    });




    // Needy ,admin routes
    Route::group(['middleware' => 'role:admin,needy'], function () {
        // Job routes
        Route::prefix('jobs')->group(callback: function () {
            Route::get('/', [JobController::class, 'index']);
            Route::get('/{id}', [JobController::class, 'show']);
        });

        // Order
        Route::get('users/{user}/orders', [OrderController::class, 'userOrders']);
    });
});
