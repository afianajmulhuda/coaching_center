<?php

use App\Http\Controllers\AccountTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ClassRoutineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\MarkEntryController;
use App\Http\Controllers\PaymentCollectionController;
use App\Http\Controllers\ResultSheetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    // Roles Resource Routes
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class); // <-- Added
    Route::resource('classes', SchoolClassController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('batches', BatchController::class);
    Route::resource('routines', ClassRoutineController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::resource('fee-types', FeeTypeController::class);
    Route::resource('student-fees', StudentFeeController::class);
    Route::resource('payment-collections', PaymentCollectionController::class);
    Route::resource('account-transactions', AccountTransactionController::class);
    Route::resource('exams', ExamController::class);
    Route::resource('exam-schedules', ExamScheduleController::class);
    Route::resource('mark-entries', MarkEntryController::class);
    Route::post('result-sheets/toggle-publish', [ResultSheetController::class, 'togglePublish'])->name('result-sheets.toggle-publish');
    Route::resource('result-sheets', ResultSheetController::class);
});