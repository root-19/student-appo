<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegistrationRequestController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// User routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

// Ticket routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::put('/tickets/{id}', [TicketController::class, 'update']);
    Route::put('/tickets/{id}/status', [TicketController::class, 'updateStatus']);
    Route::put('/tickets/{id}/priority', [TicketController::class, 'updatePriority']);
    Route::post('/tickets/{id}/appointment', [TicketController::class, 'setAppointment']);
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);
});

// Comment routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tickets/{ticketId}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});

// Document routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/documents/{id}', [DocumentController::class, 'show']);
    Route::post('/documents', [DocumentController::class, 'store']);
    Route::put('/documents/{id}/status', [DocumentController::class, 'updateStatus']);
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
});

// Notification routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications', [NotificationController::class, 'clearAll']);
});

// Registration request routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/registration-requests', [RegistrationRequestController::class, 'index']);
    Route::post('/registration-requests', [RegistrationRequestController::class, 'store']);
    Route::post('/registration-requests/{id}/approve', [RegistrationRequestController::class, 'approve']);
    Route::post('/registration-requests/{id}/reject', [RegistrationRequestController::class, 'reject']);
});

