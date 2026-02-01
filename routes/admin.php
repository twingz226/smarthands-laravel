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
use App\Http\Controllers\Admin\ContactInfoController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TrashController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\HeroMediaController;
use App\Http\Controllers\Admin\HomeMediaController;
use App\Http\Controllers\Admin\DisabledDateController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;

Route::middleware(['web','auth', 'role:admin'])->group(function () {
    // Unread count (used by some admin UIs)
    Route::get('/admin/notifications/unread-count', [NotificationController::class, 'unreadCount'])
        ->name('admin.notifications.unread_count');
    // Notification list and actions (session-auth web routes for header bell)
    // JSON list endpoint used by bell dropdown and notifications page JS
    Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    // Full page view to browse all notifications
    Route::get('/admin/notifications/all', [AdminNotificationController::class, 'index'])->name('admin.notifications.page');
    Route::post('/admin/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark_all_read');
    Route::post('/admin/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');

    Route::get('/admin/trash', [TrashController::class, 'index'])->name('admin.trash.index');
    
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Contact Messages Routes
    Route::prefix('admin/contact-messages')->name('admin.contact_messages.')->group(function () {
        Route::get('/', [AdminContactMessageController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminContactMessageController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminContactMessageController::class, 'destroy'])->name('destroy');
        Route::post('/mark-all-read', [AdminContactMessageController::class, 'markAllRead'])->name('mark_all_read');
        Route::post('/{id}/restore', [AdminContactMessageController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [AdminContactMessageController::class, 'forceDelete'])->name('force_delete');
    });

    Route::prefix('admin/customers')->name('admin.customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
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
        // Static endpoints must come BEFORE parameterized routes to avoid being captured by /{booking}
        Route::get('/time-slots', [AdminBookingController::class, 'getTimeSlots'])->name('time-slots');
        Route::get('/fully-booked-dates', [AdminBookingController::class, 'fullyBookedDates'])->name('fully.booked.dates');
        Route::get('/fully-booked-dates-json', [AdminBookingController::class, 'getFullyBookedDatesJson'])->name('fully.booked.dates.json');
        Route::get('/booked-time-slots', [AdminBookingController::class, 'getBookedTimeSlots'])->name('booked.time.slots');
        Route::get('/{booking}', [AdminBookingController::class, 'show'])->name('show');
        Route::get('/{booking}/edit', [AdminBookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}', [AdminBookingController::class, 'update'])->name('update');
        Route::patch('/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('confirm');
        Route::patch('/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('admin.cancel');
        Route::patch('/{booking}/input-price', [AdminBookingController::class, 'inputPrice'])->name('input-price');
        Route::delete('/{booking}', [AdminBookingController::class, 'destroy'])->name('destroy');
        Route::get('/{booking}/admin-reschedule', [AdminBookingController::class, 'reschedule'])->name('admin.reschedule');
        Route::patch('/{booking}/admin-reschedule', [AdminBookingController::class, 'updateReschedule'])->name('admin.update-reschedule');
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
        Route::get('/tracking', [JobController::class, 'tracking'])->name('tracking');
        Route::get('/daily-schedule', [JobController::class, 'dailySchedule'])->name('daily_schedule');
        Route::put('/tracking', [JobController::class, 'updateTracking'])->name('update-tracking');
        Route::get('/{job}', [JobController::class, 'show'])->name('show');
        Route::post('/{job}/assign', [JobController::class, 'assign'])->name('assign');
        Route::post('/{job}/complete', [JobController::class, 'complete'])->name('complete');
        Route::put('/{job}/status', [JobController::class, 'updateStatus'])->name('update-status');
        Route::match(['put', 'post'], '/{job}/reassign', [JobController::class, 'reassign'])->name('reassign');
        Route::get('/{job}/reschedule', [JobController::class, 'reschedule'])->name('reschedule');
        Route::put('/{job}/reschedule', [JobController::class, 'updateReschedule'])->name('update-reschedule');
        Route::patch('/{job}/cancel', [JobController::class, 'cancel'])->name('cancel');
        
        // Daily Schedule PDF Export Route
        Route::get('/daily-schedule/export/pdf', [JobController::class, 'exportDailySchedulePDF'])->name('daily_schedule.export.pdf');
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
        
        // Photo management routes
        Route::post('/{employee}/photos', [EmployeeController::class, 'uploadPhotos'])->name('upload-photos');
        Route::delete('/{employee}/photos', [EmployeeController::class, 'deletePhoto'])->name('delete-photo');
    });

    Route::prefix('admin/checklists')->name('checklists.')->group(function () {
        Route::get('/', [ChecklistController::class, 'index'])->name('index');
        Route::get('/add', [ChecklistController::class, 'create'])->name('add');
        Route::post('/', [ChecklistController::class, 'store'])->name('store');
        Route::get('/{checklist}/edit', [ChecklistController::class, 'edit'])->name('edit');
        Route::patch('/{checklist}', [ChecklistController::class, 'update'])->name('update');
        Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/reports')->name('reports.')->group(function () {
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/list', [ReportController::class, 'customerList'])->name('list');
            Route::get('/history', [ReportController::class, 'customerHistory'])->name('history');
            Route::get('/feedback', [ReportController::class, 'customerFeedback'])->name('feedback');
            Route::get('/retention', [ReportController::class, 'customerRetention'])->name('retention');
            
            // Export routes
            Route::prefix('export')->name('export.')->group(function () {
                // Customer list exports
                Route::get('/pdf', [ReportController::class, 'exportCustomerListPDF'])->name('pdf');
                Route::get('/csv', [ReportController::class, 'exportCustomerListCSV'])->name('csv');
                Route::get('/excel', [ReportController::class, 'exportCustomerListExcel'])->name('excel');
                
                // Cleaning history exports
                Route::get('/cleaning-history/pdf', [ReportController::class, 'exportCleaningHistoryPDF'])->name('cleaning-history.pdf');
                Route::get('/cleaning-history/csv', [ReportController::class, 'exportCleaningHistoryCSV'])->name('cleaning-history.csv');
                Route::get('/cleaning-history/excel', [ReportController::class, 'exportCleaningHistoryExcel'])->name('cleaning-history.excel');
                
                // Customer feedback exports
                Route::get('/feedback/pdf', [ReportController::class, 'exportCustomerFeedbackPDF'])->name('feedback.pdf');
                Route::get('/feedback/csv', [ReportController::class, 'exportCustomerFeedbackCSV'])->name('feedback.csv');
                Route::get('/feedback/excel', [ReportController::class, 'exportCustomerFeedbackExcel'])->name('feedback.excel');
                
                // Customer retention exports
                Route::get('/retention/pdf', [ReportController::class, 'exportCustomerRetentionPDF'])->name('retention.pdf');
                Route::get('/retention/csv', [ReportController::class, 'exportCustomerRetentionCSV'])->name('retention.csv');
                Route::get('/retention/excel', [ReportController::class, 'exportCustomerRetentionExcel'])->name('retention.excel');
            });
        });

        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/completion', [ReportController::class, 'jobCompletion'])->name('completion');
            Route::get('/assignments', [ReportController::class, 'jobAssignments'])->name('assignments');
            
            // Job completion exports
            Route::prefix('export')->name('export.')->group(function () {
                Route::get('/completion/pdf', [ReportController::class, 'exportJobCompletionPDF'])->name('completion.pdf');
                Route::get('/completion/csv', [ReportController::class, 'exportJobCompletionCSV'])->name('completion.csv');
                Route::get('/completion/excel', [ReportController::class, 'exportJobCompletionExcel'])->name('completion.excel');
            });
        });

        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/performance', [ReportController::class, 'employeePerformance'])->name('performance');
        });
    });

    // Contact Information Management
    Route::prefix('admin/contact')->name('admin.contact.')->group(function () {
        Route::get('/edit', [ContactInfoController::class, 'edit'])->name('edit');
        Route::put('/update', [ContactInfoController::class, 'update'])->name('update');
    });

    Route::prefix('admin/feedback')->name('feedback.')->group(function () {
        Route::get('/', [FeedbackController::class, 'index'])->name('index');
        Route::get('/analytics', [FeedbackController::class, 'analytics'])->name('analytics');
        Route::get('/{feedback}', [FeedbackController::class, 'show'])->name('show');
        Route::post('/{feedback}/respond', [FeedbackController::class, 'respond'])->name('respond');
    });

    // Media Management (Homepage banners and service images)
    Route::prefix('admin/media')->name('admin.media.')->group(function () {
        Route::get('/', [HomeMediaController::class, 'index'])->name('index');
        Route::post('/', [HomeMediaController::class, 'store'])->name('store');
        Route::post('/reorder', [HomeMediaController::class, 'reorder'])->name('reorder');
        Route::get('/{media}/edit', [HomeMediaController::class, 'edit'])->name('edit');
        Route::put('/{media}', [HomeMediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [HomeMediaController::class, 'destroy'])->name('destroy');
    });

    // Disabled Dates Management
    Route::prefix('admin/disabled-dates')->name('admin.disabled_dates.')->group(function () {
        Route::get('/', [DisabledDateController::class, 'index'])->name('index');
        Route::post('/', [DisabledDateController::class, 'store'])->name('store');
        Route::put('/{disabledDate}', [DisabledDateController::class, 'update'])->name('update');
        Route::delete('/{disabledDate}', [DisabledDateController::class, 'destroy'])->name('destroy');
    });

    // Legacy Hero Media routes (for backward compatibility)
    Route::prefix('admin/hero-media')->name('admin.hero_media.')->group(function () {
        Route::get('/', [HeroMediaController::class, 'index'])->name('index');
        Route::get('/create', [HeroMediaController::class, 'create'])->name('create');
        Route::post('/', [HeroMediaController::class, 'store'])->name('store');
        Route::post('/upload', [HeroMediaController::class, 'upload'])->name('upload');
        Route::post('/reorder', [HeroMediaController::class, 'reorder'])->name('reorder');
        Route::get('/{hero_medium}/edit', [HeroMediaController::class, 'edit'])->name('edit');
        Route::put('/{hero_medium}', [HeroMediaController::class, 'update'])->name('update');
        Route::delete('/{hero_medium}', [HeroMediaController::class, 'destroy'])->name('destroy');
    });

    // Admin Profile Management
    Route::prefix('admin/profile')->name('admin.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
    });

    // Company logo settings
    Route::get('settings/logo', [SettingsController::class, 'edit'])->name('admin.settings.logo.edit');
    Route::post('settings/logo', [SettingsController::class, 'update'])->name('admin.settings.logo.update');
});