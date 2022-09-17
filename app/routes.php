<?php

use App\Core\Routing\Route;
use App\Controllers\TaskController;
use App\Controllers\AuthController;

Route::get('/', [TaskController::class, 'list'])->name('home');
Route::post('/', [TaskController::class, 'store'])->name('task-store');
Route::post('/task/update', [TaskController::class, 'update'])->name('task-update')->middleware('admin');
Route::get('/task/completed', [TaskController::class, 'setCompleted'])->name('task-completed')->middleware('admin');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login-send')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('admin');