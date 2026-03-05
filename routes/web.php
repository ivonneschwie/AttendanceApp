<?php

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/', function () {
    if (session('user')) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

Route::post('/signup', [FirebaseController::class, 'signup']);
Route::post('/login', [FirebaseController::class, 'login']);
Route::get('/dashboard', [FirebaseController::class, 'dashboard']);
Route::get('/logout', [FirebaseController::class, 'logout']);
Route::get('/onboarding', [FirebaseController::class, 'onboarding']);
Route::post('/onboarding', [FirebaseController::class, 'storeOnboardingData']);
Route::get('/qrcode', [FirebaseController::class, 'qrcode']);

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [FirebaseController::class, 'adminDashboard']);
    Route::get('/admin/register-instructor', [FirebaseController::class, 'showRegisterInstructorForm']);
    Route::post('/admin/register-instructor', [FirebaseController::class, 'registerInstructor']);
});

Route::middleware('instructor')->group(function () {
    Route::get('/instructor/dashboard', [FirebaseController::class, 'instructorDashboard']);
    Route::get('/instructor/create-room', [RoomController::class, 'create']);
    Route::post('/instructor/create-room', [RoomController::class, 'store']);
    Route::get('/instructor/room/{roomCode}', [RoomController::class, 'show']);
    Route::post('/instructor/room/{roomCode}/attendance', [RoomController::class, 'createAttendanceList']);
    Route::get('/instructor/room/{roomCode}/attendance/{listId}', [RoomController::class, 'showAttendance']);
    Route::post('/instructor/room/{roomCode}/attendance/{listId}/update', [RoomController::class, 'updateAttendanceName']);
    Route::post('/instructor/room/{roomCode}/attendance/{listId}/delete', [RoomController::class, 'deleteAttendanceList']);
    Route::delete('/instructor/room/{roomCode}/attendance/{listId}/entry/{entryId}', [RoomController::class, 'deleteAttendanceEntry']);
    Route::delete('/instructor/room/{roomCode}/student/{studentId}/remove', [RoomController::class, 'removeStudent']);
});

Route::middleware('student')->group(function () {
    Route::get('/student/join-room', [StudentController::class, 'showJoinRoomForm']);
    Route::post('/student/join-room', [StudentController::class, 'joinRoom']);
    Route::get('/student/room/{roomCode}', [StudentController::class, 'show']);
});

Route::post('/api/attendance', [ApiController::class, 'markAttendance']);
