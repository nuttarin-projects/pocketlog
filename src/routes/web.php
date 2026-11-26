<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// เส้นทางสำหรับคนที่ยังไม่ล็อกอิน
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// เส้นทางที่ต้องล็อกอินก่อนเท่านั้นถึงจะเข้าได้
Route::middleware('auth')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('pocketlog.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
