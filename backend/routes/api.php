<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\CountryController;

Route::get('/countries', [CountryController::class, 'index']);
Route::post('/register', [RegistrationController::class, 'register']);
