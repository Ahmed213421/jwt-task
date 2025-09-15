<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserAuthController;
use App\Http\Controllers\Api\V1\SpecialistAuthController;
use App\Http\Controllers\Api\V1\SpecialistController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::apiResource('posts', PostController::class);

// Public service routes
Route::get('services', [ServiceController::class, 'index']);
Route::get('services/{service}', [ServiceController::class, 'show']);
Route::get('services/search', [ServiceController::class, 'search']);
Route::get('services/type/{type}', [ServiceController::class, 'byType']);

// Admin authentication routes
Route::post('admin/register', [AuthController::class, 'register']);
Route::post('admin/login', [AuthController::class, 'login']);

// User authentication routes
Route::post('user/register', [UserAuthController::class, 'register']);
Route::post('user/login', [UserAuthController::class, 'login']);

// Specialist authentication routes
Route::post('specialist/register', [SpecialistAuthController::class, 'register']);
Route::post('specialist/login', [SpecialistAuthController::class, 'login']);

// Protected admin routes
Route::middleware('auth:admin-api')->group(function () {
    Route::get('admin/user', [AuthController::class, 'user']);
    Route::post('admin/logout', [AuthController::class, 'logout']);
    Route::post('admin/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('admin/tokens', [AuthController::class, 'tokens']);
    Route::post('admin/revoke-token', [AuthController::class, 'revokeToken']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('user/profile', [UserAuthController::class, 'user']);
    Route::post('user/logout', [UserAuthController::class, 'logout']);
    
    // User booking routes
    Route::get('user/bookings', [BookingController::class, 'index']);
    Route::get('user/bookings/{booking}', [BookingController::class, 'show']);
    Route::post('user/bookings', [BookingController::class, 'store']);
    Route::put('user/bookings/{booking}', [BookingController::class, 'update']);
    Route::post('user/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});

Route::middleware('auth:specialist-api')->group(function () {
    Route::get('specialist/profile', [SpecialistAuthController::class, 'specialist']);
    Route::post('specialist/logout', [SpecialistAuthController::class, 'logout']);

    // Specialist service routes
    Route::get('specialist/services', [ServiceController::class, 'specialistServices']);
    Route::post('specialist/services', [ServiceController::class, 'store']);
    Route::put('specialist/services/{service}', [ServiceController::class, 'update']);
    Route::delete('specialist/services/{service}', [ServiceController::class, 'destroy']);
    Route::get('specialist/services-stats', [ServiceController::class, 'stats']);

    // Specialist booking routes
    Route::get('specialist/bookings', [BookingController::class, 'specialistBookings']);
    Route::get('specialist/bookings/{booking}', [BookingController::class, 'show']);
    Route::put('specialist/bookings/{booking}', [BookingController::class, 'update']);
    Route::post('specialist/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::get('specialist/bookings-stats', [BookingController::class, 'specialistStats']);
});

// Public booking routes
Route::get('specialists/{specialist}/available-slots', [BookingController::class, 'availableSlots']);
