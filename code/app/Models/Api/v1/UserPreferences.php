<?php

namespace App\Models\Api\v1;

use App\Helpers\AWSHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPreferences extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'user_preferences';
    protected $primaryKey = 'iPreferenceId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'tiSameEthnicity',
        'tiSameNationality',
        'vPreferredEarnings',
        'tiIsDrinkingPreferred',
        'tiIsDrugPreferred',
        'tiPreferredPreviouslyMarried',
        'tiLikeToHaveKids',
        'tiPreferredEducation',
        'vPreferredEducation',
        'tiPreferredAge',
        'tiPreferredReligiousBeliefs',
        'tiIsActive',
        'tsUpdatedAt',
        'tsCreatedAt',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'tsDeletedAt',
    ];

    protected $dates = [
        'tsCreatedAt',
        'tsUpdatedAt',
    ];

    # Get Discover - Home Page Listing API
    public function getDiscover($request)
    {
        $likeDeleteId = UserLike::select('iLikeId')->where('iLikeTimeExpired', '<=', time())->where('tiIsSuperLike', 0)->delete();
        try {
            $user = Auth::user();
            if ($user) {
                $iLastTimeAppUsed = User::select('iUserId','iLastTimeAppUsed')->where('iUserId',$user->iUserId)->first();
                if(!empty($iLastTimeAppUsed)){
                    $iLastTimeAppUsed->iLastTimeAppUsed = Carbon::now()->addDays(3)->timestamp;
                    $iLastTimeAppUsed->save();
                }
            }
            if ($user) {
                #code to append blocked user, reported user & liked user id from emit list
                $arrUserIds = [];
                $blockUserIds = BlockUser::getBlockedProfile($user->iUserId);
                $reportedUserIds = ReportedUser::getReportedProfile($user->iUserId);
                $likedUserIds = UserLike::getLikedProfile($user->iUserId);

                // echo "<pre>";
                // print_r($arrUserIds); die;

                # Get Fun Interests of logged in user.
                $loggedInUser = User::where(['iUserId' => $user->iUserId])->first();
                $funInterests = $loggedInUser->userFunInterests->toArray();
                $funInterestIds = array_column($funInterests, 'iInterestId');

                // echo "<pre>";
                // print_r($funInterestIds); die;

                # Get Love Langauges of logged in user.
                $loveLanguages = $loggedInUser->userLoveLanguages->toArray();
                $loveLanguageIds = array_column($loveLanguages, 'iLoveLanguageId');

                if (!empty($blockUserIds)) {
                    $arrUserIds = array_unique(array_merge($arrUserIds, $blockUserIds));
                }

                if (!empty($reportedUserIds)) {
                    $arrUserIds = array_unique(array_merge($arrUserIds, $reportedUserIds));
                }

                if (!empty($likedUserIds)) {
                    $arrUserIds = array_unique(array_merge($arrUserIds, $likedUserIds));
                }


                // $select = [
                //     'users.iUserId', DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullName"),
                //     'users.vOccupation',
                //     'users.txAboutYourSelf',
                //     'users.vProfileImage',
                //     'users.tiIsProfileImageVerified',
                //     DB::raw("TIMESTAMPDIFF(YEAR,DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(0), interval `iDob` second), '%Y-%m-%d'), CURDATE()) as iAge"),
                //     DB::raw("replace(
                //         SUBSTRING_INDEX(users.vEarnings, '-', 1), '$','') as minEarnings"),
                //     DB::raw("replace(
                //         SUBSTRING_INDEX(users.vEarnings, '-', -1), '$','') as maxEarnings")
                // ];

                $select = [
                    'users.iUserId', DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullName"),
                    'users.vOccupation',
                    'users.txAboutYourSelf',
                    'users.vProfileImage',
                    'users.tiIsProfileImageVerified',
                     DB::raw("TIMESTAMPDIFF(YEAR,DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(0), interval `iDob` second), '%Y-%m-%d'), CURDATE()) as iAge"),
                ];

                $getUsers = User::select($select)
                    ->where('users.iUserId', '!=', $user->iUserId)->where(['tiIsActive' => 1, 'tiIsProfileCompleted' => 4, 'tiIsAdminApproved' => 1])
                    ->leftjoin('user_interests', 'user_interests.iUserId', '=', 'users.iUserId')
                    ->leftjoin('user_love_languages', 'user_love_languages.iUserId', '=', 'users.iUserId');

                if (!empty($arrUserIds)) {
                    $getUsers = $getUsers->whereNotIn('users.iUserId', $arrUserIds);
                }
                # Match User Fun Interests
                if (!empty($funInterestIds)) {
                    $getUsers = $getUsers->whereIn('user_interests.iInterestId', $funInterestIds);
                }

                # Match User Love Langauges
                if (!empty($loveLanguageIds)) {
                    $getUsers = $getUsers->whereIn('user_love_languages.iLoveLanguageId', $loveLanguageIds);
                }

                # Match Ethnicity if 1
                if (!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiSameEthnicity == 1) {
                    $getUsers = $getUsers->where('users.vEthnicGroup', '=', $user->vEthnicGroup);
                }

                # Match Nationality if 1
                // if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiSameNationality == 1) {
                //     $getUsers = $getUsers->where('users.vOriginCountry','=', $user->vOriginCountry);
                // }

                # Match Drinking if preffered
                // if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiIsDrinkingPreferred == 1){
                //     $getUsers = $getUsers->where('users.tiIsDrink','=',1);
                // } else if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiIsDrinkingPreferred == 2){
                //     $getUsers = $getUsers->where('users.tiIsDrink','=',2);
                // } else if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiIsDrinkingPreferred == 3) {
                //     $getUsers = $getUsers->where('users.tiIsDrink','=',3);
                // }

                # Match Drug if preffered
                // if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiIsDrugPreferred == 1){
                //     $getUsers = $getUsers->where('users.tiUseDrugs','=',1);
                // }
                // else if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiIsDrugPreferred == 2){
                //     $getUsers = $getUsers->where('users.tiUseDrugs','=',2);
                // } else if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiIsDrugPreferred == 3) {
                //     $getUsers = $getUsers->where('users.tiUseDrugs','=',3);
                // }

                # Match Education Qualification
                // if(!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiPreferredEducation    == 1) {
                //     $getUsers = $getUsers->where('users.vEducationQualification','=',$user->userPreferences->vPreferredEducation);
                // }

                # Match Religious Preferences
                if (!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiPreferredReligiousBeliefs == 1) {
                    $getUsers = $getUsers->where(DB::raw('LOWER(users.vFaith)'), '=', strtolower($user->vFaith));
                }

                # Match Earning Preferences
                // if(!empty($loggedInUser->userPreferences)) {
                // $userPreferedEarnings = $loggedInUser->userPreferences->vPreferredEarnings;
                // $loggedInUserEarnings = explode('-',$userPreferedEarnings);
                // $loggedInUserMinEarning = trim($loggedInUserEarnings[0],'$');
                // $loggedInUserMaxEarning = trim($loggedInUserEarnings[1],'$');
                // $getUsers = $getUsers->having('minEarnings','>=',$loggedInUserMinEarning);
                // $getUsers = $getUsers->having('maxEarnings','<=',$loggedInUserMaxEarning);
                // }

                # Match Age Preferences
                $loggedInUserDob = date('Y-m-d', $user->iDob);
                $loggedInUserAge = date_diff(date_create($loggedInUserDob), date_create('now'))->y;
                if (!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiPreferredAge == 1) { # Older than me
                    $getUsers = $getUsers->having('iAge', '>=', $loggedInUserAge);
                } else if (!empty($loggedInUser->userPreferences) && $loggedInUser->userPreferences->tiPreferredAge == 2) { # Younger than me
                    $getUsers = $getUsers->having('iAge', '<=', $loggedInUserAge);
                }

                # Match 'Looking for' Preferences
                if ($loggedInUser->tiLookingFor == 1) {
                    $getUsers = $getUsers->where('users.tiLookingFor', '=', 1);
                } else if ($loggedInUser->tiLookingFor == 2) {
                    $getUsers = $getUsers->where('users.tiLookingFor', '=', 2);
                }

                # Match User Gender Inverse Preferences
                if ($loggedInUser->tiLookingFor == 1) { # Check Gender Only When tiLookingFor for set to 'Love'.
                    if ($loggedInUser->tiGender == 1) {
                        $getUsers = $getUsers->where('users.tiGender', '=', 2);
                    } else if ($loggedInUser->tiGender == 2) {
                        $getUsers = $getUsers->where('users.tiGender', '=', 1);
                    }
                }

                if ($user->tiIsPremiumUser == 0) {
                    $dataAdapter = $getUsers->orderBy('iUserId', 'DESC')->limit(10)->get()
                        ->unique('iUserId');
                } else {
                    $dataAdapter = $getUsers->orderBy('iUserId', 'DESC')->get()
                        ->unique('iUserId');
                }
                // $dataAdapter = $getUsers->orderBy('iUserId', 'DESC')->get();
                // $dataAdapter = $dataAdapter->unique('iUserId');

                if ($dataAdapter->isNotEmpty()) {
                    foreach ($dataAdapter as $getUser) {
                        $getDiscoverResponse[] = [
                            "iUserId" => $getUser->iUserId,
                            "vFullName" => $getUser->vFullName,
                            "vOccupation" => $getUser->vOccupation,
                            "txAboutYourSelf" => $getUser->txAboutYourSelf,
                            "vProfileImage" => AWSHelper::getCloundFrontUrl($getUser->vProfileImage, AWS_USER_PROFILE_IMAGE),
                            "iAge" => $getUser->iAge,
                            "tiIsProfileImageVerified" => $getUser->tiIsProfileImageVerified,
                        ];
                    }
                    return SuccessResponseWithResult($getDiscoverResponse, 'api.get_discover_success');
                } else {
                    return ErrorResponse('api.get_discover_error');
                }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }
}
