<?php

use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/signup', [FirebaseController::class, 'signup']);
Route::post('/login', [FirebaseController::class, 'login']);
