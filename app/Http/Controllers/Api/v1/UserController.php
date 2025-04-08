<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Api\v1\BlockUser;
use App\Models\Api\v1\SubscriptionPlan;
use App\Models\Api\v1\User;
use App\Models\Api\v1\UserFavouriteList;
use App\Models\Api\v1\UserLike;
use App\Models\Api\v1\UserNotification;
use App\Models\Api\v1\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    # Change Password API
    public function changePassword(Request $request) {
        $rules = [
            'vCurrentPassword' => 'required',
            'vNewPassword' => 'required|min:8|different:vCurrentPassword',
            'vConfirmPassword' => 'required_with:vNewPassword|same:vNewPassword',
        ];

        if($this->ApiValidator($request->all(), $rules)){
            return $this->SendResponse($this->response);
        }

        $model = new User();
        $response = $model->changePassword($request);
        return $this->SendResponse($response);
    }

    # Logout API
    public function logout() {
        $model = new User();
        $response = $model->logout();
        return $this->SendResponse($response);
    }

    # Edit profile API
    public function editProfile(Request $request) {
        $model = new User();
        $response = $model->editProfile($request);
        return $this->SendResponse($response);
    }

    # User Profile Details API
    public function profileDetails(Request $request) {
        $rules = [
            'vEmailId' => 'required|email',
            // 'vPassword' => 'required|min:8',
            'vConfirmPassword' => 'required_with:vPassword|same:vPassword',
            'tiGender' => 'required',
            'tiLookingFor' => 'required',
            'iDob' => 'required|numeric',
            'vZodiacSignName' => 'required',
            'vOriginCountry' => 'required',
            // 'vEthnicGroup' => 'required',
            'vLivingCountry' => 'required',
            'vCity' => 'required',
            'vFaith' => 'required',
            'tiRelationshipStatus' => 'required',
        ];

        if($this->ApiValidator($request->all(), $rules)){
            return $this->SendResponse($this->response);
        }

        $model = new User();
        $response = $model->profileDetails($request);
        return $this->SendResponse($response);
    }

    # Check Email Availability API
    public function checkEmailAvailability(Request $request) {
        $rules = [
            'vEmailId' => 'required|email',
        ];

        if($this->ApiValidator($request->all(), $rules)){
            return $this->SendResponse($this->response);
        }

        $model = new User();
        $response = $model->checkEmailAvailability($request);
        return $this->SendResponse($response);
    }

    # Profile Verification for Face-X API
    public function profileVerification(Request $request) {
        $rules = [
            'vProfileImage' => 'required',
            // 'vVideo' => 'required',
        ];

        if($this->ApiValidator($request->all(), $rules)){
            return $this->SendResponse($this->response);
        }

        $model = new User();
        $response = $model->profileVerification($request);
        return $this->SendResponse($response);
    }

    # Reset Password API
    public function resetPassword(Request $request) {
        $rules = [
            'vPassword' => 'required|min:8',
            'vConfirmPassword' => 'required_with:vPassword|same:vPassword'
        ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new User();
        $response = $model->resetPassword($request);
        return $this->SendResponse($response);
    }

    # User Preference API
    public function createPreference(Request $request) {
        $model = new User();
        $response = $model->createPreference($request);
        return $this->SendResponse($response);
    }

    # User Block API
    public function blockUser(Request $request) {
        $rules = [
            'iBlockedUserId' => 'required|numeric'
        ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new BlockUser();
        $response = $model->blockUser($request);
        return $this->SendResponse($response);
    }

    # Blocked Users List API
    public function blockUserList(Request $request) {
        $model = new BlockUser();
        $response = $model->blockUserList($request);
        return $this->SendResponse($response);
    }

    # Unblock User API
    public function unBlockUser($iBlockedUserId) {
        $model = new BlockUser();
        $response = $model->unBlockUser($iBlockedUserId);
        return $this->SendResponse($response);
    }

    # Get Other User Profile API
    public function getOtherUserProfile(Request $request) {
        $rules = [
            'iOtherUserId' => 'required|numeric'
        ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new User();
        $response = $model->getOtherUserProfile($request);
        return $this->SendResponse($response);
    }

    # User favourite / Un-favourite API
    public function userFavouriteProfile(Request $request) {
        $rules = [
            'tiTag' => 'required|in:0,1',
            'iFavouriteProfileId' => 'required|numeric',
        ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new UserFavouriteList();
        $response = $model->userFavouriteProfile($request);
        return $this->SendResponse($response);
    }

    # Get User Favourite List API
    public function userFavouriteList(Request $request) {
        $model = new UserFavouriteList();
        $response = $model->userFavouriteList($request);
        return $this->SendResponse($response);
    }

    # User Like / Un-Like Profile API
    public function userLikeProfile(Request $request) {
        $rules = [
            'tiIsLike' => 'required|in:0,1',
            'iLikeUserId' => 'required|numeric',
        ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new UserLike();
        $response = $model->userLikeProfile($request);
        return $this->SendResponse($response);
    }

    # Get User Like List API
    public function userLikeList(Request $request) {
        $model = new UserLike();
        $response = $model->userLikeList($request);
        return $this->SendResponse($response);
    }

    # Get User Match List API
    public function userMatchList(Request $request) {
        $model = new UserLike();
        $response = $model->userMatchList($request);
        return $this->SendResponse($response);
    }

    # Get User Notifications API
    public function userNotificationsList(Request $request) {
        $model = new UserNotification();
        $response = $model->userNotificationsList($request);
        return $this->SendResponse($response);
    }

    # User Update Lat / Long API
    public function updateLatLong(Request $request) {
        $rules = [
            'dbLatitude' => 'required',
            'dbLongitude' => 'required',
        ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new User();
        $response = $model->updateLatLong($request);
        return $this->SendResponse($response);
    }

    # Get Subscription Plan List
    public function getSubscriptionPlanList() {
        $model = new SubscriptionPlan();
        $response = $model->getSubscriptionPlanList();
        return $this->SendResponse($response);
    }

    # Update subscription API
    public function updateSubscription(Request $request){
        $model = new UserSubscription();
        $response = $model->updateSubscription($request);
        return $this->SendResponse($response);
    }

    #Setting API
    public function settingAPI(Request $request){
        $model = new User();
        $response = $model->settingAPI($request);
        return $this->SendResponse($response);
    }
}
