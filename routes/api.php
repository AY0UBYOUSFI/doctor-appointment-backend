<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\AvailabilityController;
use App\Http\Controllers\AppointmentController;
// 🔐 Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/doctor', [AuthController::class, 'registerDoctor']);
Route::post('/login', [AuthController::class, 'login']);

// 🛡️ Protected routes with Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // 👤 Authenticated user
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🏥 Doctors
    Route::prefix('doctors')->group(function () {
        Route::get('/', [DoctorController::class, 'index']);
        Route::post('/', [DoctorController::class, 'store']);
    });

    // 📅 Appointments
    Route::get('/appointments', [AppointmentController::class, 'index']);

    // 🧑‍⚕️ فقط المرضى يمكنهم الحجز
    Route::middleware('role:patient')->group(function () {
        Route::post('/appointments', [AppointmentController::class, 'store']);
    });

    // موافقة ورفض وجدولة المواعيد يمكن تبقى مفتوحة للطبيب أو admin
    Route::put('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
    Route::put('/appointments/{id}/reschedule', [AppointmentController::class, 'reschedule']);
    Route::put('/appointments/{id}/approve', [AppointmentController::class, 'approve']);
    Route::put('/appointments/{id}/reject', [AppointmentController::class, 'reject']);

    // ⭐ Ratings
    Route::get('/doctors/{id}/ratings', [RatingController::class, 'doctorRatings']);
    Route::get('/ratings', [RatingController::class, 'index']);
    Route::post('/ratings', [RatingController::class, 'store']);

    // 📆 Doctor Availability (restricted to doctors only)
    Route::middleware('role:doctor')->group(function () {
        Route::get('/doctor/availabilities', [AvailabilityController::class, 'index']);
        Route::post('/doctor/availabilities', [AvailabilityController::class, 'store']);
      
// 📆 Availability لأي دكتور

       // Route::post('/register/doctor', [AuthController::class, 'registerDoctor']);
        
    });
    Route::get('/doctors/{id}/availabilities', [AvailabilityController::class, 'doctorAvailability']);
});
