<?php

use Illuminate\Support\Facades\Route;
use App\Infrastructure\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
