<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\WorkoutExerciseController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::apiResource('clients', ClientController::class);


    Route::apiResource('clients.workout-plans', WorkoutPlanController::class);


    Route::put('/clients/email/{email}/workout-plans/{plan}', [WorkoutPlanController::class, 'updateByEmail']);


    Route::apiResource('workout-plans.workout-exercises', WorkoutExerciseController::class);


    Route::get('/workout-plans', [WorkoutPlanController::class, 'indexAll']);


    Route::get('/clients/{client}/workout-plans/{workout_plan}', [WorkoutPlanController::class, 'show']);

    Route::get('/messages', [MessageController::class, 'index']);     
    Route::get('/messages/sent', [MessageController::class, 'sent']);
    Route::post('/messages', [MessageController::class, 'store']); 
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead']);

    Route::get('/users', [UsersController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCountBySender'])->middleware('auth:sanctum');

});
