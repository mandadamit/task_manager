<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityLogController;


Route::middleware(['auth'])->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::resource('projects', ProjectController::class)->except(['create', 'edit', 'show']);
    Route::resource('tasks', TaskController::class)->except(['create', 'edit', 'show']);
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::post('/tasks/{task}/status', [TaskController::class, 'Status'])->name('task.status');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');