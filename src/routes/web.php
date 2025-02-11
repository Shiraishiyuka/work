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
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\ByStaffListController;
use App\Http\Controllers\Admin\ApplicationApprovalController;

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


Route::get('/admin/login', [AdminLoginController::class, 'admin_login'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');


Route::middleware(['auth', 'verified'])->group(function () {
    

    // 管理者の勤怠一覧ページは GET のみにする
    Route::match(['get', 'post'], '/admin/attendance/list/{year?}/{month?}', [AdminListController::class, 'attendance_list'])
        ->name('admin.attendance.list');

    Route::get('/admin/staff/list', [StaffListController::class, 'staff_list'])->name('admin.staff_list');

    Route::get('/admin/attendance/staff/{id}', [ByStaffListController::class, 'by_staff'])->name('by_staff');

    //申請一覧
    Route::get('/stamp_correction_request/list', [ApplicationRequestController::class, 'application_request'])->name('admin.application_request');


    Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'admin_attendance'])
        ->name('admin.attendance');

    Route::post('/admin/attendance/{id}/update', [AdminAttendanceController::class, 'update_attendance'])
        ->name('admin.attendance.update');

    Route::match(['get', 'post'],'/stamp_correction_request/approve/{attendance_correct_request}', [ApplicationApprovalController::class, 'approval'])->name('approval');
    
});