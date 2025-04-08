<?php

namespace App\Models\Api\v1;

use App\Helpers\AWSHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Api\v1\User;


class BlockUser extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';

    protected $table = 'blocked_users';
    protected $primaryKey = 'iBlockId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iUserId',
        'iBlockedUserId',
        'tsCreatedAt'
    ];

    protected $dates = [
        'tsCreatedAt'
    ];

    # Block User API
    public function blockUser($request) {
        try {
            $user = Auth::user();
            if($user) {
                # Block User
                $isExists = BlockUser::where(['iUserId' => $user->iUserId, 'iBlockedUserId' => $request->iBlockedUserId])->exists();
                if($isExists) {
                    return ErrorResponse('api.user_already_blocked');
                }
                $block_user = new BlockUser();
                $block_user->iUserId = $user->iUserId;
                $block_user->iBlockedUserId = $request->iBlockedUserId;
                $block_user->save();

                # Delete the user that is going to be blocked from the user_likes, if exists
                $getLikedUser = UserLike::where(['iUserId' => $user->iUserId, 'iLikeUserId' => $request->iBlockedUserId])->first();
                if(!empty($getLikedUser)) {
                    $getLikedUser->delete();
                }

                # Delete the user that is going to be blocked from the user_favourites_list, if exists
                $getFavouriteUser = UserFavouriteList::where(['iUserId' => $user->iUserId, 'iFavouriteProfileId' => $request->iBlockedUserId])->first();
                if(!empty($getFavouriteUser)) {
                    $getFavouriteUser->delete();
                }
                $block_user_success = User::select(DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullName"))->where(['iUserId' => $request->iBlockedUserId])->first();
                return SuccessResponse('api.block_user_success', ['vFullName' => $block_user_success->vFullName]);
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch(\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Block Users List API
    public function blockUserList($request) {
        try {
            $user = Auth::user();
            if($user) {
                $getBlockedUsers = BlockUser::where(['blocked_users.iUserId' => $user->iUserId, 'users.tiIsActive' => 1])
                ->select('users.iUserId',DB::raw("CONCAT(vFirstName,' ',vLastName) as vFullname"), 'vOccupation', 'vProfileImage',DB::raw('TIMESTAMPDIFF(YEAR, FROM_UNIXTIME(iDob), NOW()) as iAge'), 'users.tiIsProfileImageVerified')
                ->leftjoin('users', function($join){
                    $join->on('users.iUserId','=', 'blocked_users.iBlockedUserId');
                })
                ->get()->toArray();
                if(!empty($getBlockedUsers)) {
                    foreach($getBlockedUsers as $key=>$value) {
                        $getBlockedUsers[$key]['vProfileImage'] = !empty($value['vProfileImage']) ? AWSHelper::getCloundFrontUrl($value['vProfileImage'], AWS_USER_PROFILE_IMAGE) : '';
                    }
                    return SuccessResponseWithResult($getBlockedUsers, 'api.block_user_list_success');
                } else {
                    return ErrorResponse('api.no_block_users');
                }

            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch(\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # UnBlock User
    public function unBlockUser($iBlockedUserId) {
        try {
            $user = Auth::user();
            if($user) {
                BlockUser::where(['iUserId' => $user->iUserId, 'iBlockedUserId' => $iBlockedUserId])->delete();
                $unblock_user_success = User::select(DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullName"))->where(['iUserId' => $iBlockedUserId])->first();
                return SuccessResponse('api.unblock_user_success', ['vFullName' => $unblock_user_success->vFullName]);
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch(\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

     /**
     * Gets query for [[IUser]].
     *
     * @return Array list of blockProfile UserId
     */
    public static function getBlockedProfile($iUserId)
    {
        $blockedByMe = self::select(['iBlockedUserId'])->where(['iUserId' => $iUserId])->get()->toArray();
        return array_column($blockedByMe, 'iBlockedUserId');
    }


}
