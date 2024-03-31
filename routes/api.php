<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('registration', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
