<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\ContactReasonController;
use App\Http\Controllers\Backend\ContentPageController;
use App\Http\Controllers\Backend\FaqController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\ReportedUserController;
use App\Http\Controllers\Backend\ReportReasonController;
use App\Http\Controllers\Backend\UserController;
use App\Models\Backend\FunInterests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Twilio\TwiML\Video\Room;

Route::prefix('')->as('backend.')->group(function () {
    // Login Routes
    Route::get('login', [AdminController::class, 'login'])->name('login');
    Route::post('dologin', [AdminController::class, 'dologin'])->name('dologin');

    # Clear Data route
    Route::get('eraser', function () {
        Artisan::call('migrate:fresh --seed');
    });

    # Data Delete Route
    Route::get('/clear-data', function() {
        DB::delete("DELETE FROM users");
        return 'Successfully Deleted';
    });


    # Run Seeder Route
    Route::get('run-seeder', function () {
        // Artisan::call('db:seed --class=LoveLanguages');
        // Artisan::call('db:seed --class=FunInterests');
        // Artisan::call('db:seed --class=SubscriptionPlans');

        FunInterests::where(['iInterestId'=>21])->delete();
    });

    // Forgot Password Routes
    Route::get('forgot-password', [AdminController::class, 'showForgotPasswordForm'])->name('forgot_password_form');
    Route::post('forgot-password', [AdminController::class, 'submitForgotPasswordForm'])->name('forgot_password');
    Route::get('reset-password/{token}', [AdminController::class, 'showResetPasswordForm'])->name('reset_password_form');
    Route::post('reset-password-form', [AdminController::class, 'submitResetPasswordForm'])->name('reset_password');

    Route::middleware('auth:backend', 'prevent-back-history')->group(function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');

        // Home Page Route
        Route::get('', [HomeController::class, 'index'])->name('home');

        // Logout Route
        Route::get('logout', [AdminController::class, 'logout'])->name('logout');

        // Change Password Route
        Route::any('change_password', [AdminController::class, 'ChangePassword'])->name('change_password');

        // Edit Profile Route
        Route::any('profile', [AdminController::class, 'Profile'])->name('profile');

        // Manage CMS Pages Route
        Route::resource('content_page', ContentPageController::class, ['only' => ['create', 'store', 'index', 'show', 'edit', 'update']]);

        // Manage FAQs Route
        Route::resource('faqs', FaqController::class);

        // Contact Reasons Route
        Route::resource('contact-reasons', ContactReasonController::class, ['only' => ['create', 'store', 'index', 'show', 'destroy']]);

        // Report Reasons Route
        Route::resource('report-reasons', ReportReasonController::class, ['only' => ['create', 'store', 'index', 'show', 'destroy']]);

        # Reported Users List Route
        Route::resource('reported-users', ReportedUserController::class, ['only' => ['index', 'show', 'destroy']]);

        # Manage Users Route
        Route::resource('manage-users', UserController::class, ['only' => ['index']]);

        Route::get('show-notification',[NotificationController::class,'index'])->name('indexNotification');
        Route::get('update-version',[NotificationController::class,'updateVersion'])->name('updateVersion');
        Route::post('save-version',[NotificationController::class,'saveVersion'])->name('saveNewVersion');
        Route::post('send-notification',[NotificationController::class,'sendNotification'])->name('send_to_user_notification');
        # Change user Approved Status
        Route::get('approve-all-users',[UserController::class,'approveAllUsers'])->name('approveAllUsers');
        Route::post('adminApproved',[UserController::class,'adminApprovedStatus'])->name('adminApproved');
        Route::post('checkStatus',[UserController::class,'checkStatus'])->name('checkStatus');
        Route::get('view/{vUserUuid}',[UserController::class,'userView'])->name('user_view');
        Route::get('edit/{vUserUuid}',[UserController::class,'userEdit'])->name('user_edit');
        Route::post('update/{vUserUuid}',[UserController::class,'userUpdate'])->name('user_update');
        Route::get('delete/{vUserUuid}',[UserController::class,'userDelete'])->name('user_delete');
    });

    # Send push notification for no use 3 day app
    Route::get('many-days',[AdminController::class,'manyDay'])->name('many-days');
});
