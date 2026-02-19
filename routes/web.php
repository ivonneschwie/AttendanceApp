<?php

use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (session('user')) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

Route::post('/signup', [FirebaseController::class, 'signup']);
Route::post('/login', [FirebaseController::class, 'login']);
Route::get('/dashboard', [FirebaseController::class, 'dashboard']);
Route::get('/admin/dashboard', [FirebaseController::class, 'adminDashboard']);
Route::get('/logout', [FirebaseController::class, 'logout']);
Route::get('/onboarding', [FirebaseController::class, 'onboarding']);
Route::post('/onboarding', [FirebaseController::class, 'storeOnboardingData']);
Route::get('/qrcode', [FirebaseController::class, 'qrcode']);