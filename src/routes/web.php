<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\CorrectionRequestController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\Admin\AdminListController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\StaffListController;
use App\Http\Controllers\Admin\ApplicationRequestController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::match(['get', 'post'], '/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
Route::post('/attendance/start', [AttendanceController::class, 'startWork'])->name('attendance.startWork');
Route::post('/attendance/break', [AttendanceController::class, 'takeBreak'])->name('attendance.takeBreak');
Route::post('/attendance/break/end', [AttendanceController::class, 'endBreak'])->name('attendance.endBreak');
Route::post('/attendance/end', [AttendanceController::class, 'endWork'])->name('attendance.endWork');

// 勤怠一覧のルート
Route::get('/attendance_list/{year?}/{month?}', [AttendanceListController::class, 'attendance_list'])
    ->name('attendance.list');
Route::get('/attendance_list', [AttendanceListController::class, 'attendance_list'])->name('attendance_list');

Route::get('/correctionrequest', [CorrectionRequestController::class, 'correctionrequest'])->name('correctionrequest');


Route::get('/attendancedetail/{id}', [AttendanceDetailController::class, 'attendancedetail'])->name('attendancedetail');
Route::post('/attendancedetail/{id}/update', [AttendanceDetailController::class, 'update'])->name('attendancedetail.update');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');

    // 管理者の勤怠一覧ページは GET のみにする
    Route::match(['get', 'post'], '/admin/attendance/list/{year?}/{month?}', [AdminListController::class, 'attendance_list'])
        ->name('admin.attendance.list');

    Route::get('/admin/staff/list', [StaffListController::class, 'staff_list'])->name('admin.staff_list');

    //申請一覧
    Route::get('/stamp_correction_request/list', [ApplicationRequestController::class, 'application_request'])->name('admin.application_request');
});