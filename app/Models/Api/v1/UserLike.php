<?php

namespace App\Models\Api\v1;

use App\Helpers\AWSHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLike extends Model
{
    use HasFactory;
    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';

    protected $table = 'user_likes';
    protected $primaryKey = 'iLikeId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iUserId',
        'iLikeUserId',
        'tiIsLike',
        'tiIsSuperLike',
        'tsCreatedAt',
        'tsUpdatedAt',
        'iLikeTimeExpired',
    ];

    protected $dates = [
        'tsCreatedAt',
        'tsUpdatedAt'
    ];

    # User Like / Un-Like Profile API
    public function userLikeProfile($request)
    {
        try {
            $user = Auth::user();
            // echo $user->iUserId; die;
            if ($user) {
                $get_user_full_name = User::select(DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullName"))->where(['iUserId' => $request->iLikeUserId])->first();
                $loggedInUserDob = date('Y-m-d', $user->iDob);
                $loggedInUserAge = date_diff(date_create($loggedInUserDob), date_create('now'))->y;

                # Check to see if the records exists.
                $isExists = UserLike::where(['iUserId' => $user->iUserId, 'iLikeUserId' => $request->iLikeUserId])->first();

                # Check if the user that is going to be liked, already liked the user who is also going to like.
                $alreadyLiked = UserLike::where(['iLikeUserId' => $user->iUserId, 'iUserId' => $request->iLikeUserId])->first();

                if ($request->tiIsLike == 1) { # Like User Profile
                    # code to check whether user has purchased premium membership or not. If not, then allow 5 likes per day for that user.

                    #this code use to next 24 hours like
                    if ($user->tiIsPremiumUser == "0") {
                        $checkTime = User::select('iUserId', 'iLikeCount', 'iLikeTime')->where('iUserId', $user->iUserId)->first();
                        if ($checkTime->iLikeCount == 5) {
                            if ($checkTime->iLikeTime <= time()) {
                                $checkTime->iLikeCount = 0;
                                $checkTime->iLikeTime = Null;
                                $checkTime->save();
                            } else {
                                return ErrorResponse('api.like_swipe_error_for_free_user');
                            }
                        }

                        $likes = User::select('iUserId', 'iLikeCount', 'iLikeTime')->where('iUserId', $user->iUserId)->first();
                        if ($likes->iLikeCount <= 4) {
                            $likes->iLikeCount++;
                            $likes->iLikeTime = Carbon::now()->addHour(24)->timestamp;
                            $likes->save();
                        } else {
                            return ErrorResponse('api.like_swipe_error_for_free_user');
                        }
                    }
                    # end 24 hours like

                    #this code use to next day like
                    if($user->tiIsPremiumUser == "0") {
                        $totalDailyLike = self::where(['iUserId'=>$user->iUserId,'tiIsLike'=>'1'])
                        ->whereDate('tsCreatedAt', date('Y-m-d'))
                        ->count();

                        if($totalDailyLike >= 5) {
                            return ErrorResponse('api.like_swipe_error_for_free_user');
                        }
                    }
                    # Ends Here

                    $match = 0;
                    DB::beginTransaction();
                    if ($isExists) {
                        return ErrorResponse('api.already_added_to_likes');
                    }
                    $add_to_like = new UserLike();
                    $add_to_like->iUserId = $user->iUserId;
                    $add_to_like->iLikeUserId = $request->iLikeUserId;
                    $add_to_like->tiIsLike = 1;
                    $add_to_like->iLikeTimeExpired = Carbon::now()->addHour(72)->timestamp;
                    if ($alreadyLiked) { # Set tiSuperLike = 1
                        $alreadyLiked->tiIsSuperLike = 1; # set 1 to the user who previously liked the user.
                        $add_to_like->tiIsSuperLike = 1;
                        $match = 1;
                        $alreadyLiked->save();

                        # Add entry to notification table for the matched user.
                        $matchNotificationData = [
                            'iRecievedUserId' => $request->iLikeUserId,
                            'iSendUserId' => $user->iUserId,
                            'vNotificationTitle' => 'Match found for you',
                            'vNotificationDesc' => 'You\'ve got a new match 😍😍',
                            'tiNotificationType' => '2',
                        ];
                        UserNotification::create($matchNotificationData);
                        $msgData = [
                            // 'title' => $user->vFirstName.' '. $user->vLastName,
                            'title' => 'Super Like',
                            'msg' => 'Match found for you 😍😍',
                            'badge' => 0,
                            'sound' => 'default',
                            'type' => '2',
                            'iUserId' => $user->iUserId,
                            'iAge' => $loggedInUserAge,
                            'vProfileImage' => $user->vProfileImage,
                            'tiIsProfileImageVerified' => $user->tiIsProfileImageVerified,
                        ];
                        # Ends Here
                    }
                    $add_to_like->save();

                    # Add entry to notification table for the liked user.
                    if ($match == 0) {
                        $likeNotificationData = [
                            'iRecievedUserId' => $request->iLikeUserId,
                            'iSendUserId' => $user->iUserId,
                            'vNotificationTitle' => 'Like',
                            'vNotificationDesc' => 'You\'ve got a new like 😍😍',
                            'tiNotificationType' => '1',
                            'iNotificationTimeExprie' => Carbon::now()->addHour(72)->timestamp,
                        ];

                        UserNotification::create($likeNotificationData);

                        $msgData = [
                            // 'title' => $user->vFirstName.' '. $user->vLastName,
                            'title' => 'Like',
                            'msg' => 'You\'ve got a new like 😍😍',
                            'badge' => 0,
                            'sound' => 'default',
                            'type' => '1',
                            'iUserId' => $user->iUserId,
                            'iAge' => $loggedInUserAge,
                            'vProfileImage' => $user->vProfileImage,
                            'tiIsProfileImageVerified' => $user->tiIsProfileImageVerified,
                        ];
                    }
                    # Notification Enrty Code Ends Here

                    # Android Notification Push
                    # Find Device Token
                    $registrationIds = Device::where(['iUserId' => $request->iLikeUserId])
                        ->first();

                    if (!empty($registrationIds->vDeviceToken) && $registrationIds->tiDeviceType == 1) {
                        sendPushAndroid($registrationIds->vDeviceToken, $msgData);
                    }
                    DB::commit();
                    return SuccessResponse('api.user_added_to_likes', ['vFullName' => $get_user_full_name->vFullName]);
                } else { # Un-Like User Profile
                    if (!empty($isExists)) {
                        if (!empty($alreadyLiked)) {
                            $alreadyLiked->tiIsSuperLike = 0; # Set tiSuperLike to 0.
                            $alreadyLiked->save();
                        }
                        // echo "un like"; die;
                        $isExists->delete(); # Remove from like.

                        $deleteNotification = UserNotification::select('iUserNotificationId', 'iRecievedUserId')
                            ->where(['iRecievedUserId' => $isExists->iLikeUserId, 'iSendUserId' => $user->iUserId, 'tiNotificationType' => 1, 'vNotificationTitle' => 'Like'])
                            ->first();
                        if (!empty($deleteNotification)) {
                            $deleteNotification->delete();
                        }
                        // print_r($deleteNotification); die;
                        return SuccessResponse('api.user_remove_from_likes', ['vFullName' => $get_user_full_name->vFullName]);
                    } else {
                        return ErrorResponse('api.user_not_found');
                    }
                }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Get User Like List API
    public function userLikeList($request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                // if($user->tiIsActive == 1) {
                $getLikedUsers = UserLike::where(['user_likes.tiIsLike' => 1,  'user_likes.iUserId' => $user->iUserId, 'users.tiIsActive' => 1])
                    ->select(DB::raw("CONCAT(vFirstName,' ',vLastName) as vFullName"), DB::raw("TIMESTAMPDIFF(YEAR,DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(0), interval `iDob` second), '%Y-%m-%d'), CURDATE()) as iAge"), 'vOccupation', 'vProfileImage', 'user_likes.tsCreatedAt', 'users.iUserId', 'users.tiIsProfileImageVerified', 'user_likes.iLikeTimeExpired', 'user_likes.tiIsSuperLike')
                    ->leftjoin('users', 'users.iUserId', '=', 'user_likes.iLikeUserId')
                    ->orderBy('user_likes.tsCreatedAt', 'DESC')->get();

                // if($user->tiIsPremiumUser == 0) {
                //     $getLikedUsers->where(DB::raw("user_likes.tsCreatedAt + INTERVAL 5 HOUR"), '>=', now());
                // } else {
                //     $getLikedUsers->where(DB::raw("user_likes.tsCreatedAt + INTERVAL 72 HOUR"), '>=', now());
                // }

                // $getLikedUsers = $getLikedUsers->get();

                if ($getLikedUsers->isNotEmpty()) {
                    return SuccessResponseWithResult($this->getUserLikeResponse($getLikedUsers), 'api.user_like_list_success');
                } else {
                    return ErrorResponse('api.user_not_found');
                }
                // } else {
                //     return ErrorResponse('api.unauthenticated');
                // }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Get User Match List API
    public function userMatchList($request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                // if($user->tiIsActive == 1) {
                    $getMatchedUsers = UserLike::select('users.iUserId', 'users.vProfileImage', DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullName"), 'users.tiIsProfileImageVerified', 'users.vQuickBloxUserId', 'users.vQbLogin')
                        ->leftjoin('users', 'users.iUserId', '=', 'user_likes.iLikeUserId')
                        ->where(['user_likes.iUserId' => $user->iUserId, 'user_likes.tiIsLike' => 1, 'user_likes.tiIsSuperLike' => 1, 'users.tiIsActive' => 1])
                        ->get();

                    if ($getMatchedUsers->isNotEmpty()) {
                        return SuccessResponseWithResult($this->getUserMatchResponse($getMatchedUsers), 'api.user_match_list_success');
                    } else {
                        return ErrorResponse('api.user_not_found');
                    }
                // } else {
                    // return ErrorResponse('api.unauthenticated');
                // }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Get the logged in users, liked user ids
    public static function getLikedProfile($iUserId)
    {
        $likedByMe = UserLike::select(['iLikeUserId'])->where(['iUserId' => $iUserId])->get()->toArray();
        // print_r($likedByMe); die;
        return array_column($likedByMe, 'iLikeUserId');
    }

    # Get User Like Response Function
    public function getUserLikeResponse($result)
    {

        // print_r($result[0]['iLikeTimeExpired']); die;
        // echo "ok"; die;
        foreach ($result as $user) {
            // print_r($user->toArray());
            if ($user->iLikeTimeExpired >=  Carbon::now()->timestamp || $user->tiIsSuperLike == 1) {
                $response[] = [
                    "iUserId" => $user->iUserId,
                    "vFullName" => $user->vFullName,
                    "iAge" => $user->iAge,
                    "vOccupation" => $user->vOccupation,
                    "vProfileImage" => AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE),
                    "tiIsProfileImageVerified" => $user->tiIsProfileImageVerified,
                    "tsCreatedAt" => TimeElapsedString($user->tsCreatedAt),
                ];
            }
        }
        // foreach ($result as $user) {
        //     if ($user->tiIsSuperLike == 1) {
        //         $response[] = [
        //             "iUserId" => $user->iUserId,
        //             "vFullName" => $user->vFullName,
        //             "iAge" => $user->iAge,
        //             "vOccupation" => $user->vOccupation,
        //             "vProfileImage" => AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE),
        //             "tiIsProfileImageVerified" => $user->tiIsProfileImageVerified,
        //             "tsCreatedAt" => TimeElapsedString($user->tsCreatedAt),
        //         ];
        //     }
        // }
        return $response;
    }

    # Get User Match Response Function
    public function getUserMatchResponse($result)
    {
        foreach ($result as $user) {
            $response[] = [
                "iUserId" => $user->iUserId,
                "vProfileImage" => AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE),
                "vFullName" => $user->vFullName,
                "tiIsProfileImageVerified" => $user->tiIsProfileImageVerified,
                "vQuickBloxUserId" => !empty($user->vQuickBloxUserId) ? $user->vQuickBloxUserId : '',
                "vQbLogin" => !empty($user->vQbLogin) ? $user->vQbLogin : '',

            ];
        }
        return $response;
    }
}
