<?php

use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\CourseRegistrationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseRegistrationController::class, 'create'])->name('home');
Route::get('/register', [CourseRegistrationController::class, 'create'])->name('registration.create');
Route::get('/programmes/{program}/courses', [CourseRegistrationController::class, 'courses'])->name('registration.courses');
Route::post('/register', [CourseRegistrationController::class, 'store'])->middleware('throttle:10,1')->name('registration.store');
Route::get('/registration-slip/{student}', [CourseRegistrationController::class, 'slip'])->name('registration.slip');
Route::get('/find-registration', [CourseRegistrationController::class, 'lookup'])->name('registration.lookup');
Route::post('/find-registration', [CourseRegistrationController::class, 'find'])->middleware('throttle:5,1')->name('registration.find');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::post('programs/import', [ProgramsController::class, 'import'])->name('programs.import');
        Route::post('courses/import', [CoursesController::class, 'import'])->name('courses.import');
        Route::resource('programs', ProgramsController::class);
        Route::resource('courses', CoursesController::class);
        Route::resource('students', StudentsController::class);
    });
});
