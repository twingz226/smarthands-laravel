<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ChecklistController;
use App\Http\Controllers\Admin\ReportController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin/customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/portal', [CustomerController::class, 'portal'])->name('portal');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminBookingController::class, 'index'])->name('index');
        Route::get('/create', [AdminBookingController::class, 'create'])->name('create');
        Route::post('/', [AdminBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [AdminBookingController::class, 'show'])->name('show');
        Route::get('/{booking}/edit', [AdminBookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}', [AdminBookingController::class, 'update'])->name('update');
        Route::patch('/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('confirm');
        Route::patch('/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('cancel');
        Route::delete('/{booking}', [AdminBookingController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/jobs')->name('jobs.')->group(function () {
        Route::get('/dispatch', [JobController::class, 'dispatch'])->name('dispatch');
        Route::post('/{job}/assign', [JobController::class, 'assign'])->name('assign');
        Route::get('/tracking', [JobController::class, 'tracking'])->name('tracking');
        Route::post('/{job}/complete', [JobController::class, 'complete'])->name('complete');
        Route::put('/{job}/status', [JobController::class, 'updateStatus'])->name('update-status');
        Route::put('/{job}/reassign', [JobController::class, 'reassign'])->name('reassign');
    });

    Route::prefix('admin/employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/performance', [EmployeeController::class, 'performance'])->name('performance');
        Route::get('/assignments', [EmployeeController::class, 'assignments'])->name('assignments');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/checklists')->name('checklists.')->group(function () {
        Route::get('/', [ChecklistController::class, 'index'])->name('index');
        Route::post('/', [ChecklistController::class, 'store'])->name('store');
        Route::patch('/{checklist}', [ChecklistController::class, 'update'])->name('update');
        Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/reports')->name('reports.')->group(function () {
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/list', [ReportController::class, 'customerList'])->name('list');
            Route::get('/history', [ReportController::class, 'customerHistory'])->name('history');
            Route::get('/feedback', [ReportController::class, 'customerFeedback'])->name('feedback');
            Route::get('/retention', [ReportController::class, 'customerRetention'])->name('retention');
        });

        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/completion', [ReportController::class, 'jobCompletion'])->name('completion');
            Route::get('/assignments', [ReportController::class, 'jobAssignments'])->name('assignments');
        });

        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/performance', [ReportController::class, 'employeePerformance'])->name('performance');
        });
    });
});