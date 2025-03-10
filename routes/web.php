<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentCOntroller;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login',[AuthController::class,'index'])->name('index');
Route::post('/login',[AuthController::class,'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function(){
    
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/tasks/calendar', [DashboardController::class, 'getCalendarData'])->name('tasks.getCalendarTasks');
    Route::get('/task-counts', [DashboardController::class, 'getTaskCounts'])->name('tasks.counts');
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('/tasks',TaskController::class); 
        Route::resource('/categories',CategoryController::class); 
        Route::resource('/users',UserController::class);;
        Route::post('/tasks/{id}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    });

});

