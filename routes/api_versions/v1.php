<?php

use App\Http\Controllers\Api\v1\ContactReasonController;
use App\Http\Controllers\Api\v1\FunInterestController;
use App\Http\Controllers\Api\v1\LoveLanguagesController;
use App\Http\Controllers\Api\v1\MembershipController;
use App\Http\Controllers\Api\v1\OauthController;
use App\Http\Controllers\Api\v1\ReportReasonController;
use App\Http\Controllers\Api\v1\SettingController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('api.')->group(function () {

    # Content Page by Slug API Route
    Route::get('content-pages/{vSlug}', [SettingController::class, 'contentPages']);

    # FAQ List API Route
    Route::get('faqs', [SettingController::class, 'faqs']);

    # Contact Reason List API Route
    Route::get('contact-reasons', [ContactReasonController::class, 'getContactReasons']);

    # Report Reason List API Route
    Route::get('report-reasons', [ReportReasonController::class, 'reportReasons']);

    # Check Email Availability API Route
    Route::post('user/check-email-availability', [UserController::class, 'checkEmailAvailability']);

    # Fun Interest List API Route
    Route::get('fun-interests', [FunInterestController::class, 'funInterests']);

    # Love Languages List API Route
    Route::get('love-languages', [LoveLanguagesController::class, 'loveLanguages']);

    Route::prefix('oauth')->group(function () {

        # Signup Step 1 API Route
        Route::post('signup', [OauthController::class, 'signup']);

        # Request for OTP API Route
        Route::post('request-for-otp', [OauthController::class, 'sendOTP']);

        # Verify OTP API Route
        Route::post('verify-otp', [OauthController::class, 'verifyOTP']);

        # Login API Route
        Route::post('login', [OauthController::class, 'login']);

        # Social Login API Route
        Route::post('social-signin', [OauthController::class, 'socialSignin']);
    });

    # Setting API Route
    Route::post('setting-api', [UserController::class, 'settingAPI']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('user')->group(function () {

            # Logout API Route
            Route::get('logout', [UserController::class, 'logout']);

            # User Change Password API Route
            Route::post('change-password', [UserController::class, 'changePassword']);

            # User Profile Details (Signup Step 2) API Route
            Route::post('profile-details', [UserController::class, 'profileDetails']);

            # User Profile Verification (Signup Step 3) API Route
            Route::post('profile-verification', [UserController::class, 'profileVerification']);

            # User Reset Password API Route
            Route::post('reset-password', [UserController::class, 'resetPassword']);

            # User Edit Profile API Route
            Route::post('/edit-profile', [UserController::class, 'editProfile']);

            # User Preferences API Route
            Route::post('create-preference', [UserController::class, 'createPreference']);

            # User Contact Reason API Route
            Route::post('create-contact-reason', [ContactReasonController::class, 'createContactReason']);

            # User Report Reason API Route
            Route::post('create-report-reason', [ReportReasonController::class, 'createReportReason']);

            # User Block API Route
            Route::post('block-user', [UserController::class, 'blockUser']);

            # Blocked Users List API Route
            Route::get('block-user-list', [UserController::class, 'blockUserList']);

            # UnBlock User API Route
            Route::delete('unblock-user/{iBlockedUserId}', [UserController::class, 'unBlockUser']);

            # Get Other User Profile Route
            Route::post('get-other-user-profile', [UserController::class, 'getOtherUserProfile']);

            # User Favourite / Un Favourite API Route
            Route::post('favourite-profile', [UserController::class, 'userFavouriteProfile']);

            # User Favourite List API Route
            Route::get('favourite-list', [UserController::class, 'userFavouriteList']);

            # User Like / Un Like API Route
            Route::post('like-profile', [UserController::class, 'userLikeProfile']);

            # User Like List API Route
            Route::get('like-list', [UserController::class, 'userLikeList']);

            # User Match List API Route
            Route::get('match-list', [UserController::class, 'userMatchList']);

            # Get User Notifications API Route
            Route::get('/notifications', [UserController::class, 'userNotificationsList']);

            # User Update Lat / Long
            Route::post('update-lat-long', [UserController::class, 'updateLatLong']);

            # Get Subscription List API Route
            Route::get('/subscription-plan-list', [UserController::class, 'getSubscriptionPlanList']);

            # Update Subscription API Route
            Route::post('update-subscription',[UserController::class,'updateSubscription']);
        });

        # User Preferences
        Route::prefix('user-preference')->group(function () {
            # Get Discover API Route
            Route::get('get-discover', [UserPreferenceController::class, 'getDiscover']);
        });
    });
});
