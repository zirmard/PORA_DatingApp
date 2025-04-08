<?php

namespace App\Models\Api\v1;

use App\Helpers\AWSHelper;
use App\Helpers\QuickBloxService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $primaryKey = 'iUserId';

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'vFirstName',
        'vLastName',
        'vISDCode',
        'vMobileNumber',
        'iOTP',
        'iOTPExpireAt',
        'vEmailId',
        'tiGender',
        'tiLookingFor',
        'iDob',
        'vZodiacSignName',
        'vOriginCountry',
        'vEthnicGroup',
        'vLivingCountry',
        'vCity',
        'vFaith',
        'tiRelationshipStatus',
        'vProfileImage',
        'vVideo',
        'vTimeZone',
        'dbLatitude',
        'dbLongitude',
        'vOccupation',
        'vEarnings',
        'tiIsDrink',
        'tiUseDrugs',
        'tiBelieveInMarriage',
        'tiHaveKids',
        'vEducationQualification',
        'txAboutYourSelf',
        'txDealBreaker',
        'tiIsProfileImageVerified',
        'tiIsProfileCompleted',
        'vQuickBloxUserId',
        'vQbLogin',
        'iLikeCount',
        'iLikeTime',
        'iLastTimeAppUsed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'tiIsActive', 'tsCreatedAt', 'tsUpdatedAt', 'tsDeletedAt',
    ];

    protected $attributes = [
        'tiIsPremiumUser' => 0,
        'tiIsSocialLogin' => 0,
        'tiIsMobileVerified' => 0,
        'tiIsActive' => 1,
    ];

    public function userFunInterests()
    {
        return $this->hasMany(UserInterests::class, 'iUserId', 'iUserId');
    }

    public function userLoveLanguages()
    {
        return $this->hasMany(UserLoveLanguages::class, 'iUserId', 'iUserId');
    }

    public function userPreferences()
    {
        return $this->hasOne(UserPreferences::class, 'iUserId', 'iUserId');
    }

    # Sign Up (Step - 1) API
    public function signup($request)
    {
        try {
            $isMobileVerified = User::where(['vISDCode' => $request->vISDCode, 'vMobileNumber' => $request->vMobileNumber])->first();

            if ($isMobileVerified) {
                if ($isMobileVerified->tiIsMobileVerified == 0) {
                    return SuccessResponseWithResult($request->all(), 'api.signup_success');
                } else {
                    return ErrorResponse('api.mobile_already_exists');
                }
            } else {
                $twilioResponse = twilioNumberIsValidCheck($request->vISDCode . $request->vMobileNumber);
                if ($twilioResponse['responseCode'] == 400) {
                    return ErrorResponse('api.mobile_not_valid');
                }
                DB::beginTransaction();
                if ($request->vSocialId) {
                    $checkUser = SocialAccount::select('iUserId')->where(['vSocialId' => $request->vSocialId])->first();
                }
                if (!empty($checkUser)) {
                    $user = User::where(['iUserId' => $checkUser->iUserId])->first();
                } else {
                    $user = new User();
                }
                $user->fill($request->all());
                $user->vUserUuid = GetUuid();
                if ($user->save()) {
                    $this->CreateOrUpdateDevice($user->iUserId, $user->createToken(config('app.app_secret'))->plainTextToken, $request);
                    $response = [
                        'vFirstName' => $user->vFirstName,
                        'vLastName' => $user->vLastName,
                        'vISDCode' => $user->vISDCode,
                        'vMobileNumber' => $user->vMobileNumber,
                    ];
                    DB::commit();
                    return SuccessResponseWithResult($response, 'api.signup_success');
                }
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    //Validate email address
    private function IsEmailRegistered($vEmailId)
    {
        $email_result = User::select("vUserUuid")
            ->whereRaw("LOWER(vEmailId) = '" . strtolower($vEmailId) . "' AND tiIsDeleted = 0")
            ->first();

        return (!empty($email_result)) ? true : false;
    }

    private function CreateOrUpdateDevice($iUserId, $vAccessToken, $request)
    {
        $device = Device::where("iUserId", $iUserId)->first();
        if (!empty($device)) {
            $device->vAccessToken = $vAccessToken;
            $device->vDeviceToken = $request->vDeviceToken ?? $device->vDeviceToken;
            $device->tiDeviceType = $request->tiDeviceType ?? 1;
            $device->vDeviceName = $request->vDeviceName ?? "";
            $device->iUpdatedAt = time();
            $device->update();
        } else {
            $model = new Device();
            $model->vAccessToken = $vAccessToken;
            $model->iUserId = $iUserId;
            $model->vDeviceToken = $request->vDeviceToken;
            $model->tiDeviceType = $request->tiDeviceType ?? 1;
            $model->vDeviceName = $request->vDeviceName ?? "";
            $model->iCreatedAt = time();
            $model->save();
        }
    }

    # Request For OTP API
    public function sendOTP($request)
    {
        try {
            $account_sid = env("TWILIO_SID");
            $auth_token = env("TWILIO_TOKEN");
            $twilio_number = env("TWILIO_FROM");

            $user = User::where(['vISDCode' => $request->vISDCode, 'vMobileNumber' => $request->vMobileNumber])->first();
            if (!empty($user)) {
                // $user->iOTP = mt_rand(1000,9999);
                $user->iOTP = 1234;
                $user->iOTPExpireAt = strtotime(OTP_EXPIRE_AT);
                // $to_number = $request->vISDCode.$request->vMobileNumber;
                if ($user->save()) {
                    // $body = "Welcome to Pora! OTP (One Time Password) for your request is : " .$user->iOTP." "."Kindly do not share the OTP with anyone else.";
                    // $client = new Client($account_sid, $auth_token);
                    // $message = $client->messages->create($to_number, [
                    //         'from' => $twilio_number,
                    //         'body' => $body
                    //     ]);
                    // if($message->sid) {
                    return SuccessResponse('api.send_otp_success');
                    // }
                }
            } else {
                return ErrorResponse('api.mobile_not_found');
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Verify OTP API
    public function verifyOTP($request)
    {
        try {
            $user = User::where(['vISDCode' => $request->vISDCode, 'vMobileNumber' => $request->vMobileNumber, 'iOTP' => $request->iOTP])->first();
            if (!empty($user)) {
                if (time() > $user->iOTPExpireAt) {
                    return ErrorResponse('api.otp_expired');
                } else {
                    DB::beginTransaction();
                    $user->iOTP = null;
                    $user->iOTPExpireAt = null;
                    $user->tiIsMobileVerified = 1;
                    $user->tiIsProfileCompleted = 1;
                    $user->save();
                    $user_data = $this->GetUserData($user, true);
                    $this->CreateOrUpdateDevice($user->iUserId, $user_data['vAccessToken'], $request);
                    DB::commit();
                    return SuccessResponseWithResult($user_data, 'api.verify_otp_success');
                }
            } else {
                return ErrorResponse('api.verify_otp_error');
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Login API
    public function login($request)
    {
        try {
            DB::beginTransaction();
            $user = User::where(["vEmailId" => $request->vEmailId])->whereNull('tsDeletedAt')->first();
            if (!empty($user)) {
                if (!Hash::check($request->vPassword, $user->vPassword)) {
                    return ErrorResponse('api.login_user_not_found');
                }
            } else {
                return ErrorResponse('api.login_user_not_found');
            }
            $user->tokens()->delete();
            if ($user->tiIsActive != 1) {
                return ErrorResponse('api.account_inactive');
            }
            // if ($user->tiIsAdminApproved != 1) {
            //     return ErrorResponse('api.account_notapproved');
            // }
            $user->update();
            $user_data = $this->GetUserData($user, true);
            $this->CreateOrUpdateDevice($user->iUserId, $user_data['vAccessToken'], $request);
            DB::commit();
            return SuccessResponseWithResult($user_data, "api.login_success");
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Create QuickBlox User
    public static function createQuickBloxUser($user, $blobid = "")
    {
        # code to check whether the user already exist on quickblox or not
        $exitsUser = QuickBloxService::checkQbUserExits($user->vEmailId);
        if (is_array($exitsUser) && isset($exitsUser['data']['id'])) {
            $userDob = date('Y-m-d', $user->iDob);
            $age = date_diff(date_create($userDob), date_create('now'))->y;

            $customData = "";
            $customKeyData = []; //for store image and age into the quickblox
            $customKeyData = [
                "vProfileImage" => AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE),
                "iAge" => $age,
                "iUserId" => $user->iUserId,
                "tiIsProfileImageVerified" => $user->tiIsProfileImageVerified,
            ];

            $customData = json_encode($customKeyData);

            $new_user_info = [
                'user[full_name]' => $user->vFirstName,
                'user[email]' => $user->vEmailId,
                'user[blob_id]' => $blobid,
                'user[custom_data]' => $customData,
            ];
            $obj = new QuickBloxService();
            $return = $obj->QuickBloxUpdateUserById($exitsUser['data']['id'], $user->vQbLogin, $new_user_info);
            return (isset($return['user']['id'])) ? $return : false;
        } else if ($exitsUser['code'] == 404) {
            $userDob = date('Y-m-d', $user->iDob);
            $age = date_diff(date_create($userDob), date_create('now'))->y;

            $customData = "";
            $customKeyData = []; //for store image and age into the quickblox
            $customKeyData = [
                "vProfileImage" => AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE),
                "iAge" => $age,
                "iUserId" => $user->iUserId,
                "tiIsProfileImageVerified" => $user->tiIsProfileImageVerified,
            ];

            $customData = json_encode($customKeyData);

            $newUserInfo = array(
                'user[email]' => $user->vEmailId,
                'user[login]' => env('QB_USER_PREFIX') . $user->iUserId,
                'user[password]' => env('QB_Password'),
                'user[full_name]' => $user->vFirstName,
                'user[custom_data]' => $customData,
            );
            $result = QuickBloxService::QuickBloxUserCreate($newUserInfo);
            return (isset($result['user']['id'])) ? $result : false;
        } else {
            return false;
        }
    }

    # Social signin API
    public function socialSignin($request)
    {
        $social_user = GetSocialId($request->tiSocialType, $request->vSocialId);

        if ($social_user['responseCode'] == 200) {
            $data = $social_user['responseData'];

            $user = User::from("users as u")->select("u.*")
                ->join("social_accounts as s", "u.iUserId", "=", "s.iUserId")
                ->where(["s.vSocialId" => $data['vSocialId'], "s.tiSocialType" => $data['tiSocialType'], "u.tiIsSocialLogin" => 1])
                ->whereNull('tsDeletedAt')
                ->first();

            if (empty($user->iUserId)) {

                DB::beginTransaction();

                if (!empty($data["vEmailId"])) {
                    $model = User::where(['vEmailId' => $data['vEmailId']])->whereNull('tsDeletedAt')->first();
                }

                if (empty($model->iUserId)) {
                    unset($model);
                    $model = new User();
                    $model->vUserUuid = GetUuid();
                    $model->vEmailId = $data["vEmailId"] ?? "";
                }

                $model->tiIsSocialLogin = 1;
                $model->save();

                $social_model = new SocialAccount();
                $social_model->iUserId = $model->iUserId;
                $social_model->vSocialId = $data['vSocialId'];
                $social_model->tiSocialType = $data['tiSocialType'];
                // $social_model->vImageUrl = $data['vImageUrl'];
                $social_model->iCreatedAt = time();
                $social_model->iUpdatedAt = time();
                $social_model->save();

                $user_data = $this->GetUserData($model, true);
                $this->CreateOrUpdateDevice($model->iUserId, $user_data['vAccessToken'], $request);
                DB::commit();
                return SuccessResponseWithResult($user_data, "api.login_success");
            } else {
                if ($user->tiIsActive != 1) {
                    return ErrorResponse('api.account_inactive');
                }

                DB::beginTransaction();

                # start SocialAccount
                $userAr = array_filter([
                    'vEmailId' => !empty($data["vEmailId"]) ? $data["vEmailId"] : '',
                    'tiIsSocialLogin' => 1,
                ]);

                User::where('iUserId', $user->iUserId)->update($userAr);

                # end SocialAccount

                # start SocialAccount
                $socialAccount = array_filter([
                    'vImageUrl' => !empty($data['vImageUrl']) ? $data['vImageUrl'] : '',
                    'iUpdatedAt' => time(),
                ]);
                SocialAccount::where('iUserId', $user->iUserId)->update($socialAccount);
                # end SocialAccount

                $user_data = $this->GetUserData($user, true);
                $this->CreateOrUpdateDevice($user->iUserId, $user_data['vAccessToken'], $request);
                DB::commit();
                return SuccessResponseWithResult($user_data, "api.login_success");
            }
        } else {
            return $social_user;
        }
    }

    # Change Password API
    public function changePassword($request)
    {
        try {
            $user = Auth::user();
            if (Hash::check($request->vCurrentPassword, $user->vPassword)) {
                User::where('iUserId', $user->iUserId)
                    ->update([
                        'vPassword' => Hash::make($request->vNewPassword),
                    ]);
                return SuccessResponse('api.change_password_success');
            } else {
                return ErrorResponse('api.incorrect_old_password');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Logout API
    public function logout()
    {
        try {
            $user = Auth::user();
            Device::where(['iUserId' => $user->iUserId])->delete();
            $user->tokens()->delete();
            return SuccessResponse('api.logout_success');
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Edit Profile API
    public function editProfile($request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                DB::beginTransaction();
                $loggedInUser = User::where(['iUserId' => $user->iUserId])->first();

                if (!empty($loggedInUser->userPreferences)) {
                    $user_preference = $loggedInUser->userPreferences;
                } else {
                    $user_preference = new UserPreferences();
                    $user_preference->iUserId = $user->iUserId;
                }

                # Update 'About Me' Tab Details
                $user->tiLookingFor = !empty($request->tiLookingFor) ? $request->tiLookingFor : $user->tiLookingFor;

                $user->vOriginCountry = !empty($request->vOriginCountry) ? $request->vOriginCountry : $user->vOriginCountry;

                $user->vEthnicGroup = !empty($request->vEthnicGroup) ? $request->vEthnicGroup : $user->vEthnicGroup;

                $user->vLivingCountry = !empty($request->vLivingCountry) ? $request->vLivingCountry : $user->vLivingCountry;

                $user->vCity = !empty($request->vCity) ? $request->vCity : $user->vCity;

                $user->vFaith = !empty($request->vFaith) ? $request->vFaith : $user->vFaith;

                $user->tiRelationshipStatus = !empty($request->tiRelationshipStatus) ? $request->tiRelationshipStatus : $user->tiRelationshipStatus;

                # Update 'Preferences' Tab Details
                $user_preference->tiSameEthnicity = !empty($request->tiSameEthnicity) ? $request->tiSameEthnicity : $user_preference->tiSameEthnicity;

                $user->vOccupation = !empty($request->vOccupation) ? $request->vOccupation : $user->vOccupation;

                $user->vEarnings = !empty($request->vEarnings) ? $request->vEarnings : $user->vEarnings;

                $user_preference->vPreferredEarnings = !empty($request->vPreferredEarnings) ? $request->vPreferredEarnings : $user_preference->vPreferredEarnings;

                $user->tiIsDrink = !empty($request->tiIsDrink) ? $request->tiIsDrink : $user->tiIsDrink;

                $user_preference->tiIsDrinkingPreferred = !empty($request->tiIsDrinkingPreferred) ? $request->tiIsDrinkingPreferred : $user_preference->tiIsDrinkingPreferred;

                $user->tiUseDrugs = !empty($request->tiUseDrugs) ? $request->tiUseDrugs : $user->tiUseDrugs;

                $user_preference->tiIsDrugPreferred = !empty($request->tiIsDrugPreferred) ? $request->tiIsDrugPreferred : $user_preference->tiIsDrugPreferred;

                $user->tiBelieveInMarriage = !empty($request->tiBelieveInMarriage) ? $request->tiBelieveInMarriage : $user->tiBelieveInMarriage;

                $user->tiHaveKids = !empty($request->tiHaveKids) ? $request->tiHaveKids : $user->tiHaveKids;

                $user_preference->tiLikeToHaveKids = !empty($request->tiLikeToHaveKids) ? $request->tiLikeToHaveKids : $user_preference->tiLikeToHaveKids;

                if ($request->tiPreferredEducation == 1) {
                    $user_preference->vPreferredEducation = $request->vPreferredEducation;
                    $user_preference->tiPreferredEducation = $request->tiPreferredEducation;
                } else if ($request->tiPreferredEducation == 2 || $request->tiPreferredEducation == 3) {
                    $user_preference->vPreferredEducation = '';
                    $user_preference->tiPreferredEducation = $request->tiPreferredEducation;
                }
                $user_preference->vPreferredEducation = $request->tiPreferredEducation == 1 ? $request->vPreferredEducation : $user_preference->vPreferredEducation;

                $user_preference->tiPreferredAge = !empty($request->tiPreferredAge) ? $request->tiPreferredAge : $user_preference->tiPreferredAge;

                $user_preference->tiSameNationality = !empty($request->tiSameNationality) ? $request->tiSameNationality : $user_preference->tiSameNationality;

                $user_preference->tiPreferredReligiousBeliefs = !empty($request->tiPreferredReligiousBeliefs) ? $request->tiPreferredReligiousBeliefs : $user_preference->tiPreferredReligiousBeliefs;

                $user_preference->tiPreferredPreviouslyMarried = !empty($request->tiPreferredPreviouslyMarried) ? $request->tiPreferredPreviouslyMarried : $user_preference->tiPreferredPreviouslyMarried;

                $user->txAboutYourSelf = !empty($request->txAboutYourSelf) ? $request->txAboutYourSelf : $user->txAboutYourSelf;

                $user->txDealBreaker = !empty($request->txDealBreaker) ? $request->txDealBreaker : $user->txDealBreaker;

                $user->vEducationQualification = !empty($request->vEducationQualification) ? $request->vEducationQualification : $user->vEducationQualification;

                # Update User Fun Interests
                if (!empty($request->iInterestId)) {
                    $insertIds = explode(',', $request->iInterestId);
                    foreach ($insertIds as $insertId) {
                        $user_interests_array[] = [
                            'iUserId' => $user->iUserId,
                            'iInterestId' => $insertId,
                        ];
                    }
                    UserInterests::where(['iUserId' => $user->iUserId])->delete();
                    DB::table('user_interests')->insert($user_interests_array);
                } else {
                    UserInterests::where(['iUserId' => $user->iUserId])->delete(); # Delete Old User Interests
                }

                # Update User Love Languages
                if (!empty($request->iLoveLanguageId)) {
                    $loveLanguageIds = explode(',', $request->iLoveLanguageId);
                    foreach ($loveLanguageIds as $loveLanguageId) {
                        $user_love_languages_array[] = [
                            'iUserId' => $user->iUserId,
                            'iLoveLanguageId' => $loveLanguageId,
                        ];
                    }
                    UserLoveLanguages::where(['iUserId' => $user->iUserId])->delete();
                    DB::table('user_love_languages')->insert($user_love_languages_array);
                } else {
                    UserLoveLanguages::where(['iUserId' => $user->iUserId])->delete(); # Delete Old User Love Languages
                }

                # Update 'Edit Profile' Tab Details
                if (!empty($request->vEmailId)) { # Update Email Address
                    $isEmailExists = User::where(['vEmailId' => $request->vEmailId, 'tiIsActive' => 1])->exists();
                    if ($isEmailExists && $user->vEmailId != $request->vEmailId) {
                        return ErrorResponse('api.email_already_exist');
                    } else {
                        $user->vEmailId = $request->vEmailId;
                    }
                }

                # Update Gender
                $user->tiGender = !empty($request->tiGender) ? $request->tiGender : $user->tiGender;

                # Update Profile Video
                $user->vVideo = !empty($request->vVideo) ? $request->vVideo : $user->vVideo;

                # Update Images
                if (!empty($request->vImageName)) {
                    $imageNames = explode(',', $request->vImageName);
                    foreach ($imageNames as $imageName) {
                        $user_images_array[] = [
                            'iUserId' => $user->iUserId,
                            'vImageName' => $imageName,
                            'vImageUuid' => Str::uuid()->toString(),
                        ];
                    }
                    # Set User Profile Image in users table.
                    $user->vProfileImage = $user_images_array[0]['vImageName'];

                    UserImages::where(['iUserId' => $user->iUserId])->delete();
                    DB::table('user_images')->insert($user_images_array);
                } else {
                    UserImages::where(['iUserId' => $user->iUserId])->delete(); # Delete Old User Images
                    $user->vProfileImage = ''; # Empty the profile image of the user.
                }

                $user->save();
                $user_preference->save();

                # code to create user on QuickBlox
                $result = self::createQuickBloxUser($user);
                if ($result !== false) {
                    $user->vQuickBloxUserId = $result['user']['id'];
                    $user->vQbLogin = $result['user']['login'];
                    $user->save();
                }

                DB::commit();
                return SuccessResponseWithResult($this->GetUserData($user), "api.profile_updated");
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Profile Details API (signup step 2)
    public function profileDetails($request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->vEmailId = $request->vEmailId;
                $user->vPassword = !empty($request->vPassword) ? Hash::make($request->vPassword) : '';
                $user->tiGender = $request->tiGender;
                $user->tiLookingFor = $request->tiLookingFor;
                $user->iDob = $request->iDob;
                $user->vZodiacSignName = $request->vZodiacSignName;
                $user->vOriginCountry = $request->vOriginCountry;
                $user->vEthnicGroup = !empty($request->vEthnicGroup) ? $request->vEthnicGroup : '';
                $user->vLivingCountry = $request->vLivingCountry;
                $user->vCity = $request->vCity;
                $user->vFaith = $request->vFaith;
                $user->tiRelationshipStatus = $request->tiRelationshipStatus;
                $user->tiIsProfileCompleted = 2;
                $user->save();

                # code to create user on QuickBlox
                $result = self::createQuickBloxUser($user);
                if ($result !== false) {
                    $user->vQuickBloxUserId = $result['user']['id'];
                    $user->vQbLogin = $result['user']['login'];
                    $user->save();
                }
                return SuccessResponseWithResult($this->GetUserData($user), 'api.profile_details_success');
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Check Email Availability API
    public function checkEmailAvailability($request)
    {
        try {
            $isEmailExists = User::where(['vEmailId' => $request->vEmailId])->exists();
            if ($isEmailExists) {
                return ErrorResponse('api.email_already_exist');
            } else {
                return SuccessResponse('api.email_available');
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Profile Verification API for Face-X API Integration
    public function profileVerification($request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                DB::beginTransaction();
                $user->vProfileImage = $request->vProfileImage;
                $user->vSelfieImage = $request->vSelfieImage;
                $user->vVideo = !empty($request->vVideo) ? $request->vVideo : '';
                $user->tiIsProfileImageVerified = 3; // uploaded
                $user->tiIsProfileCompleted = 3; // completed till phase 3
                $user->save();
                $user_image = new UserImages();
                $user_image->vImageName = $user->vProfileImage;
                $user_image->iUserId = $user->iUserId;
                $user_image->vImageUuId = Str::uuid()->toString();
                $user_image->save();

                # code to create user on QuickBlox
                $result = self::createQuickBloxUser($user);
                if ($result !== false) {
                    $user->vQuickBloxUserId = $result['user']['id'];
                    $user->vQbLogin = $result['user']['login'];
                    $user->save();
                }

                DB::commit();
                return SuccessResponseWithResult($this->GetUserData($user), 'api.profile_upload_success');
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Reset Password API
    public function resetPassword($request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->vPassword = Hash::make($request->vPassword);
                if ($user->save()) {
                    return SuccessResponse('api.reset_password_success');
                }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Create User Preference
    public function createPreference($request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                # save user details
                DB::beginTransaction();
                $user->vOccupation = !empty($request->vOccupation) ? $request->vOccupation : '';
                $user->vEarnings = !empty($request->vEarnings) ? $request->vEarnings : '';
                $user->tiIsDrink = !empty($request->tiIsDrink) ? $request->tiIsDrink : 0;
                $user->tiUseDrugs = !empty($request->tiUseDrugs) ? $request->tiUseDrugs : 0;
                $user->tiBelieveInMarriage = !empty($request->tiBelieveInMarriage) ? $request->tiBelieveInMarriage : 0;
                $user->tiHaveKids = !empty($request->tiHaveKids) ? $request->tiHaveKids : 0;
                $user->vEducationQualification = !empty($request->vEducationQualification) ? $request->vEducationQualification : '';
                $user->txAboutYourSelf = !empty($request->txAboutYourSelf) ? $request->txAboutYourSelf : '';
                $user->txDealBreaker = !empty($request->txDealBreaker) ? $request->txDealBreaker : '';

                # save user preferences
                $userPreferenceExists = UserPreferences::where(['iUserId' => $user->iUserId])->first();
                if (empty($userPreferenceExists)) {
                    $user_preferences = new UserPreferences();
                } else {
                    $user_preferences = $userPreferenceExists;
                }
                $user_preferences->iUserId = $user->iUserId;

                $user_preferences->tiSameEthnicity = !empty($request->tiSameEthnicity) ? (int) $request->tiSameEthnicity : 0;
                $user_preferences->tiSameNationality = !empty($request->tiSameNationality) ? (int) $request->tiSameNationality : 0;

                $user_preferences->vPreferredEarnings = !empty($request->vPreferredEarnings) ? $request->vPreferredEarnings : '';

                $user_preferences->tiIsDrinkingPreferred = !empty($request->tiIsDrinkingPreferred) ? (int) $request->tiIsDrinkingPreferred : 0;

                $user_preferences->tiIsDrugPreferred = !empty($request->tiIsDrugPreferred) ? (int) $request->tiIsDrugPreferred : 0;

                $user_preferences->tiPreferredPreviouslyMarried = !empty($request->tiPreferredPreviouslyMarried) ? (int) $request->tiPreferredPreviouslyMarried : 0;

                $user_preferences->tiLikeToHaveKids = !empty($request->tiLikeToHaveKids) ? (int) $request->tiLikeToHaveKids : 0;

                $user_preferences->tiPreferredEducation = !empty($request->tiPreferredEducation) ? (int) $request->tiPreferredEducation : 0;

                $user_preferences->vPreferredEducation = $request->tiPreferredEducation == 1 ? $request->vPreferredEducation : '';

                $user_preferences->tiPreferredAge = !empty($request->tiPreferredAge) ? (int) $request->tiPreferredAge : 0;

                $user_preferences->tiPreferredReligiousBeliefs = !empty($request->tiPreferredReligiousBeliefs) ? (int) $request->tiPreferredReligiousBeliefs : 0;

                $user_preferences->save();

                # save user fun interests
                if (!empty($request->iInterestId)) {
                    $userInterestsExists = UserInterests::where(['iUserId' => $user->iUserId])->first();
                    if (empty($userInterestsExists)) {
                        $user_interests = new UserInterests();
                    } else {
                        $user_interests = $userInterestsExists;
                    }
                    UserInterests::where(['iUserId' => $user->iUserId])->delete();
                    $user_interests->iUserId = $user->iUserId;
                    $insertIds = explode(',', $request->iInterestId);
                    foreach ($insertIds as $insertId) {
                        $user_interests_array[] = [
                            'iUserId' => $user->iUserId,
                            'iInterestId' => $insertId,
                        ];
                    }
                    DB::table('user_interests')->insert($user_interests_array);
                }

                # save user love languages
                if (!empty($request->iLoveLanguageId)) {
                    $userLoveLanguagesExists = UserLoveLanguages::where(['iUserId' => $user->iUserId])->first();
                    if (empty($userLoveLanguagesExists)) {
                        $user_love_languages = new UserLoveLanguages();
                    } else {
                        $user_love_languages = $userLoveLanguagesExists;
                    }
                    UserLoveLanguages::where(['iUserId' => $user->iUserId])->delete();
                    $user_love_languages->iUserId = $user->iUserId;
                    $loveLanguageIds = explode(',', $request->iLoveLanguageId);
                    foreach ($loveLanguageIds as $loveLanguageId) {
                        $user_love_languages_array[] = [
                            'iUserId' => $user->iUserId,
                            'iLoveLanguageId' => $loveLanguageId,
                        ];
                    }
                    DB::table('user_love_languages')->insert($user_love_languages_array);
                }
                # Set Full Profile Complete Flag
                $user->tiIsProfileCompleted = 4;
                $user->save();
                DB::commit();
                return SuccessResponseWithResult($this->GetUserData($user), 'api.create_user_preference_success');
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Get Other User Profile API
    public function getOtherUserProfile($request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                $getOtherUser = User::select(
                    DB::raw("CONCAT(vFirstName,' ',vLastName) as vFullname"),
                    DB::raw('TIMESTAMPDIFF(YEAR, FROM_UNIXTIME(iDob), NOW()) as iAge'),
                    'vOccupation',
                    'vCity',
                    'vEthnicGroup',
                    'txAboutYourSelf',
                    'vLivingCountry',
                    'iUserId',
                    'dbLatitude',
                    'tiGender',
                    'dbLongitude',
                    'tiIsProfileImageVerified',
                    'vFaith',
                    DB::raw('3959*acos(cos(radians( "' . $request->dbLatitude . '" ))*cos(radians(`users`.`dbLatitude`))*cos(radians(`users`.`dbLongitude`)-radians("' . $request->dbLongitude . '"))+sin(radians("' . $request->dbLatitude . '"))*sin(radians(`users`.`dbLatitude`))) AS distance')
                )
                    ->where(['iUserId' => $request->iOtherUserId, 'tiIsActive' => 1])
                    ->first();

                $isUserFavourite = UserFavouriteList::where(['iUserId' => $user->iUserId, 'iFavouriteProfileId' => $request->iOtherUserId])->first();

                $isUserLike = UserLike::where(['iUserId' => $request->iOtherUserId, 'iLikeUserId' => $user->iUserId, 'tiIsLike' => 1, 'tiIsSuperLike' => 0])->first();

                $getLoggedInUserInterestIdsArr = UserInterests::select('iInterestId')->where(['iUserId' => $user->iUserId])->get()->toArray();

                $getLoggedInUserInterestIds = array_column($getLoggedInUserInterestIdsArr, 'iInterestId');

                $iMatchedInterestsCount = UserInterests::where(['iUserId' => $request->iOtherUserId])->whereIn('iInterestId', $getLoggedInUserInterestIds)->count();

                $isLikedByLoggedInUser = UserLike::where(['iUserId' => $user->iUserId, 'iLikeUserId' => $request->iOtherUserId])->first();

                if (!empty($getOtherUser)) {
                    return SuccessResponseWithResult($this->getOtherUserProfileResponse($getOtherUser, $isUserFavourite, $isUserLike, $iMatchedInterestsCount, $isLikedByLoggedInUser), 'api.get_other_user_profile_success');
                } else {
                    return ErrorResponse('api.user_not_found');
                }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Update User Lat / Long API
    public function updateLatLong($request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->dbLatitude = $request->dbLatitude;
                $user->dbLongitude = $request->dbLongitude;
                $user->save();
                return SuccessResponseWithResult($this->GetUserData($user), "api.lat_long_updated");
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Get Other User Profile Details Response
    public function getOtherUserProfileResponse($user, $isUserFavourite = '', $isUserLike = '', $iMatchedInterestsCount = '', $isLikedByLoggedInUser = '')
    {
        $result = [
            "vAccessToken" => request()->bearerToken(),
            "iUserId" => $user->iUserId,
            "vFullName" => $user->vFullname,
            "iAge" => $user->iAge,
            "vOccupation" => $user->vOccupation,
            "vNationality" => $user->vLivingCountry,
            "vCity" => $user->vCity,
            "vEthnicGroup" => $user->vEthnicGroup,
            "iMatchedInterestsCount" => !empty($iMatchedInterestsCount) ? $iMatchedInterestsCount : 0,
            "txAboutYourSelf" => $user->txAboutYourSelf,
            "tiIsProfileImageVerified" => $user->tiIsProfileImageVerified,
            "tiIsUserLike" => !empty($isUserLike) ? 1 : 0,
            "isLikedByLoggedInUser" => !empty($isLikedByLoggedInUser) ? 1 : 0,
            "tiIsUserFavourite" => !empty($isUserFavourite) ? 1 : 0,
            "vDistance" => (string) round($user->distance, 2),
            "tiGender" => $user->tiGender,
            "userFunInterests" => self::getUserFunInterests($user->iUserId),
            "userImages" => self::getUserImages($user->iUserId),
            "userLoveLanguages" => self::getUserLoveLanguages($user->iUserId),
            "vFaith" => $user->vFaith,
        ];
        return $result;
    }

    # Get User Images
    public static function getUserImages($iUserId)
    {
        $userImagesArr = UserImages::where(['iUserId' => $iUserId])
            ->select('iImageId', 'vImageName')
            ->get()->toArray();
        if (!empty($userImagesArr)) {
            foreach ($userImagesArr as $userImage) {
                $userImages[] = [
                    'iImageId' => $userImage['iImageId'],
                    'vImageName' => AWSHelper::getCloundFrontUrl($userImage['vImageName'], AWS_USER_PROFILE_IMAGE),
                ];
            }
            return $userImages;
        } else {
            return [];
        }
    }

    # Get User Love Languages
    public static function getUserLoveLanguages($iUserId)
    {
        $userLoveLanguages = UserLoveLanguages::where(['iUserId' => $iUserId])
            ->select('love_languages.iLoveLanguageId', 'love_languages.vLoveLanguage', 'love_languages.vLoveLanguageLogo')
            ->leftjoin('love_languages', function ($join) {
                $join->on('user_love_languages.iLoveLanguageId', '=', 'love_languages.iLoveLanguageId');
            })
            ->get()->toArray();
        foreach ($userLoveLanguages as $key => $userLoveLanguage) {
            $userLoveLanguages[$key]['vLoveLanguageLogo'] = AWSHelper::getCloundFrontUrl($userLoveLanguage['vLoveLanguageLogo'], AWS_LOVE_LANGUAGE_ICONS);
        }
        return $userLoveLanguages;
    }

    # Get User Interests
    public static function getUserFunInterests($iUserId)
    {
        $userFunInterests = UserInterests::where(['iUserId' => $iUserId])
            ->select('fun_interests.iInterestId', 'fun_interests.vInterestName', 'fun_interests.vInterestLogo')
            ->leftjoin('fun_interests', function ($join) {
                $join->on('user_interests.iInterestId', '=', 'fun_interests.iInterestId');
            })
            ->get()->toArray();
        foreach ($userFunInterests as $key => $userFunInterest) {
            $userFunInterests[$key]['vInterestLogo'] = AWSHelper::getCloundFrontUrl($userFunInterest['vInterestLogo'], AWS_FUN_INTEREST_ICONS);
        }
        return $userFunInterests;
    }

    #Setting API
    public function settingAPI($request)
    {
        #new code
        $vAccessToken = Device::select('iUserId')->where('vAccessToken', $request->token)->first();
        if (!empty($vAccessToken)) {
            try {
                $userToken = Device::select('iUserId')->where('vAccessToken', $request->token)->first();
                if (!empty($userToken)) {
                    $subscriptionData = UserSubscription::select('ltxSubscriptionToken', 'vProductId', 'vPackageName')->where('iUserId', $userToken->iUserId)->first();
                }
                if (!empty($subscriptionData)) {
                    $result = AndroidVerifySubscription($subscriptionData);
                    if($result['status'] == true){
                        $subscriptionData = UserSubscription::where('iUserId', $userToken->iUserId)
                        ->update([
                            // 'vProductId' => $request['vProductId'],
                            // 'vPackageName' => $request['vPackageName'],
                            // 'vOrderId' => $request['vOrderId'],
                            // 'ltxSubscriptionToken' => $request['ltxSubscriptionToken'],
                            'dtSubscriptionStartDate' => $result['data']['startTimeMillis'],
                            'tiIsAutoRenewing' => $result['data']['autoRenewing'],
                            'dtSubscriptionEndDate' => $result['data']['expiryTimeMillis']
                        ]);
                    }
                    // echo "<pre>";
                    // print_r($result); die;
                    if ($result['status'] == false) {
                        $subscriptionData = UserSubscription::where('iUserId', $userToken->iUserId)
                            ->update([
                                'ltxSubscriptionToken' => NUll,
                                'dtSubscriptionStartDate' => 0,
                                'dtSubscriptionEndDate' => 0
                            ]);
                        $tiIsPremiumUser = User::where('iUserId', $userToken->iUserId)
                            ->update([
                                'tiIsPremiumUser' => 0
                            ]);
                        $vAccessToken = Device::select('iUserId')->where('vAccessToken', $request->token)->first();
                        if (!empty($vAccessToken)) {
                            $user = User::select('tiIsAdminApproved', 'tiIsPremiumUser')->where('iUserId', $vAccessToken->iUserId)->first();
                            $endDate = UserSubscription::select('dtSubscriptionEndDate', 'ltxSubscriptionToken')->where('iUserId', $vAccessToken->iUserId)->first();
                            return SuccessResponseWithResult(
                                [
                                    'tiIsAdminApproved' => (int) $user->tiIsAdminApproved,
                                    'dtSubscriptionEndDate' => !empty($endDate->dtSubscriptionEndDate) ? $endDate->dtSubscriptionEndDate : "",
                                    'ltxSubscriptionToken' => !empty($endDate->ltxSubscriptionToken) ? $endDate->ltxSubscriptionToken : "",
                                    'tiIsPremiumUser' => (int) $user->tiIsPremiumUser
                                ],
                                "success"
                            );
                        } else {
                            return SuccessResponseWithResult(['tiIsAdminApproved' => 2], "success");
                        }
                    } else {
                        $vAccessToken = Device::select('iUserId')->where('vAccessToken', $request->token)->first();
                        if (!empty($vAccessToken)) {
                            $user = User::select('tiIsAdminApproved', 'tiIsPremiumUser')->where('iUserId', $vAccessToken->iUserId)->first();
                            $endDate = UserSubscription::select('dtSubscriptionEndDate', 'ltxSubscriptionToken')->where('iUserId', $vAccessToken->iUserId)->first();
                            return SuccessResponseWithResult(
                                [
                                    'tiIsAdminApproved' => (int) $user->tiIsAdminApproved,
                                    'dtSubscriptionEndDate' => !empty($endDate->dtSubscriptionEndDate) ? $endDate->dtSubscriptionEndDate : "",
                                    'ltxSubscriptionToken' => !empty($endDate->ltxSubscriptionToken) ? $endDate->ltxSubscriptionToken : "",
                                    'tiIsPremiumUser' => (int) $user->tiIsPremiumUser
                                ],
                                "success"
                            );
                        } else {
                            return SuccessResponseWithResult(['tiIsAdminApproved' => 2], "success");
                        }
                    }
                } else {
                    if (!empty($vAccessToken)) {
                        $user = User::select('tiIsAdminApproved', 'tiIsPremiumUser')->where('iUserId', $vAccessToken->iUserId)->first();
                        $endDate = UserSubscription::select('dtSubscriptionEndDate', 'ltxSubscriptionToken')->where('iUserId', $vAccessToken->iUserId)->orderBy('iUserSubscriptionId', 'DESC')->first();
                        return SuccessResponseWithResult(
                            [
                                'tiIsAdminApproved' => (int) $user->tiIsAdminApproved,
                                'dtSubscriptionEndDate' => !empty($endDate->dtSubscriptionEndDate) ? $endDate->dtSubscriptionEndDate : "",
                                'ltxSubscriptionToken' => !empty($endDate->ltxSubscriptionToken) ? $endDate->ltxSubscriptionToken : "",
                                'tiIsPremiumUser' => (int) $user->tiIsPremiumUser
                            ],
                            "success"
                        );
                    } else {
                        return SuccessResponseWithResult(['tiIsAdminApproved' => 2], "success");
                    }
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            if (!empty($vAccessToken)) {
                $user = User::select('tiIsAdminApproved', 'tiIsPremiumUser')->where('iUserId', $vAccessToken->iUserId)->first();
                $endDate = UserSubscription::select('dtSubscriptionEndDate', 'ltxSubscriptionToken')->where('iUserId', $vAccessToken->iUserId)->orderBy('iUserSubscriptionId', 'DESC')->first();
                return SuccessResponseWithResult(
                    [
                        'tiIsAdminApproved' => (int) $user->tiIsAdminApproved,
                        'dtSubscriptionEndDate' => !empty($endDate->dtSubscriptionEndDate) ? $endDate->dtSubscriptionEndDate : "",
                        'ltxSubscriptionToken' => !empty($endDate->ltxSubscriptionToken) ? $endDate->ltxSubscriptionToken : "",
                        'tiIsPremiumUser' => (int) $user->tiIsPremiumUser
                    ],
                    "success"
                );
            } else {
                return SuccessResponseWithResult(['tiIsAdminApproved' => 2], "success");
            }
        }
        #end new code
    }

    # User Response Data
    private function GetUserData($user, $flag = false)
    {
        $vSocialId = SocialAccount::select('vSocialId')->where(['iUserId' => $user->iUserId])->first();
        $vProfilePic = "";
        if ($user->tiIsSocialLogin == 1) {
            $vProfilePic = $user->vProfilePic;
        } else {
            $vProfilePic = (!empty($user->vProfilePic)) ? url(Storage::url("uploads/" . $user->vProfilePic)) : "";
        }

        $getUserPreferences = UserPreferences::where(['iUserId' => $user->iUserId])->first();

        $result = [
            "vSocialId" => !empty($vSocialId) ? (string) $vSocialId->vSocialId : '',
            "vAccessToken" => ($flag) ? $user->createToken(config('app.app_secret'))->plainTextToken : request()->bearerToken(),
            "iUserId" => $user->iUserId,
            "vFirstName" => $user->vFirstName ?? "",
            "vLastName" => $user->vLastName ?? "",
            "vISDCode" => $user->vISDCode ?? "",
            "vMobileNumber" => $user->vMobileNumber ?? "",
            "vEmailId" => !empty($user->vEmailId) ? $user->vEmailId : '',
            "dbLatitude" => $user->dbLatitude ?? "",
            "dbLongitude" => $user->dbLongitude ?? "",
            "tiIsPremiumUser" => $user->tiIsPremiumUser,
            "tiIsSocialLogin" => $user->tiIsSocialLogin,
            "tiIsMobileVerified" => $user->tiIsMobileVerified,
            "tiGender" => !empty($user->tiGender) ? (int) $user->tiGender : 0,
            "tiLookingFor" => !empty($user->tiLookingFor) ? (int) $user->tiLookingFor : 0,
            "iDob" => !empty($user->iDob) ? (int) $user->iDob : 0,
            "vZodiacSignName" => !empty($user->vZodiacSignName) ? $user->vZodiacSignName : '',
            "vOriginCountry" => !empty($user->vOriginCountry) ? $user->vOriginCountry : '',
            "vEthnicGroup" => !empty($user->vEthnicGroup) ? $user->vEthnicGroup : '',
            "vEducationQualification" => !empty($user->vEducationQualification) ? $user->vEducationQualification : '',
            "vLivingCountry" => !empty($user->vLivingCountry) ? $user->vLivingCountry : '',
            "txAboutYourSelf" => !empty($user->txAboutYourSelf) ? $user->txAboutYourSelf : '',
            "txDealBreaker" => !empty($user->txDealBreaker) ? $user->txDealBreaker : '',
            "vCity" => !empty($user->vCity) ? $user->vCity : '',
            "vFaith" => !empty($user->vFaith) ? $user->vFaith : '',
            "tiRelationshipStatus" => !empty($user->tiRelationshipStatus) ? (int) $user->tiRelationshipStatus : 0,
            "vProfileImage" => !empty($user->vProfileImage) ? AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE) : '',
            "vSelfieImage" => !empty($user->vSelfieImage) ? AWSHelper::getCloundFrontUrl($user->vSelfieImage, AWS_USER_SELFIE_IMAGE) : '',
            "vVideo" => !empty($user->vVideo) ? AWSHelper::getCloundFrontUrl($user->vVideo, AWS_USER_VERIFICATION_VIDEO) : '',
            "vQuickBloxUserId" => !empty($user->vQuickBloxUserId) ? (string) $user->vQuickBloxUserId : '',
            "vQbLogin" => !empty($user->vQbLogin) ? $user->vQbLogin : '',
            "vQbPassword" => config('services.quickblox.password'),
            "tiIsProfileImageVerified" => !empty($user->tiIsProfileImageVerified) ? (int) $user->tiIsProfileImageVerified : 0,
            "tiIsDrink" => !empty($user->tiIsDrink) ? (int) $user->tiIsDrink : 0,
            "tiUseDrugs" => !empty($user->tiUseDrugs) ? (int) $user->tiUseDrugs : 0,
            "tiBelieveInMarriage" => !empty($user->tiBelieveInMarriage) ? (int) $user->tiBelieveInMarriage : 0,
            "tiHaveKids" => !empty($user->tiHaveKids) ? (int) $user->tiHaveKids : 0,
            "tiSameEthnicity" => !empty($getUserPreferences->tiSameEthnicity) ? (int) $getUserPreferences->tiSameEthnicity : 0,
            "tiSameNationality" => !empty($getUserPreferences->tiSameNationality) ? (int) $getUserPreferences->tiSameNationality : 0,
            "vOccupation" => !empty($user->vOccupation) ? $user->vOccupation : '',
            "vEarnings" => !empty($user->vEarnings) ? $user->vEarnings : '',
            "vPreferredEarnings" => !empty($getUserPreferences->vPreferredEarnings) ? $getUserPreferences->vPreferredEarnings : '',
            "tiIsDrinkingPreferred" => !empty($getUserPreferences->tiIsDrinkingPreferred) ? (int) $getUserPreferences->tiIsDrinkingPreferred : 0,
            "tiIsDrugPreferred" => !empty($getUserPreferences->tiIsDrugPreferred) ? (int) $getUserPreferences->tiIsDrugPreferred : 0,
            "tiPreferredPreviouslyMarried" => !empty($getUserPreferences->tiPreferredPreviouslyMarried) ? (int) $getUserPreferences->tiPreferredPreviouslyMarried : 0,
            "tiLikeToHaveKids" => !empty($getUserPreferences->tiLikeToHaveKids) ? (int) $getUserPreferences->tiLikeToHaveKids : 0,
            "tiPreferredEducation" => !empty($getUserPreferences->tiPreferredEducation) ? (int) $getUserPreferences->tiPreferredEducation : 0,
            "vPreferredEducation" => !empty($getUserPreferences->vPreferredEducation) ? $getUserPreferences->vPreferredEducation : '',
            "tiPreferredAge" => !empty($getUserPreferences->tiPreferredAge) ? (int) $getUserPreferences->tiPreferredAge : 0,
            "tiPreferredReligiousBeliefs" => !empty($getUserPreferences->tiPreferredReligiousBeliefs) ? (int) $getUserPreferences->tiPreferredReligiousBeliefs : 0,
            "tiIsProfileCompleted" => !empty($user->tiIsProfileCompleted) ? (int) $user->tiIsProfileCompleted : 0,
            "userLoveLanguages" => self::getUserLoveLanguages($user->iUserId),
            "userFunInterests" => self::getUserFunInterests($user->iUserId),
            "userImages" => self::getUserImages($user->iUserId),
            "tsCreatedAt" => Carbon::parse($user->tsCreatedAt)->format('Y-m-d'),
            "tiIsAdminApproved" => !empty($user->tiIsAdminApproved) ? (int) $user->tiIsAdminApproved : 0,
        ];
        return $result;
    }
}
