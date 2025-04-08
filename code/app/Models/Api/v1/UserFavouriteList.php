<?php

namespace App\Models\Api\v1;

use App\Helpers\AWSHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserFavouriteList extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';

    protected $table = 'user_favourites_list';
    protected $primaryKey = 'iFavouriteId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iUserId',
        'iFavouriteProfileId',
        'tsCreatedAt'
    ];

    protected $dates = [
        'tsCreatedAt'
    ];

     # Add User to favourites / Remove User from favourites based on tag value.
     public function userFavouriteProfile($request) {
        try {
            $user = Auth::user();
            if($user) {

                $get_user_full_name = User::select(DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullName"))->where(['iUserId' => $request->iFavouriteProfileId])->first();

                $isExists = UserFavouriteList::where(['iUserId' => $user->iUserId, 'iFavouriteProfileId' => $request->iFavouriteProfileId])->first();
                if($request->tiTag == 1) {
                    if($isExists) {
                        return ErrorResponse('api.already_added_to_favourites');
                    }
                    $add_to_favourite = new UserFavouriteList();
                    $add_to_favourite->iUserId = $user->iUserId;
                    $add_to_favourite->iFavouriteProfileId = $request->iFavouriteProfileId;
                    $add_to_favourite->save();
                    return SuccessResponse('api.user_added_to_favourites',['vFullName' => $get_user_full_name->vFullName]);
                } else {
                    if(!empty($isExists)) {
                        $isExists->delete();
                        return SuccessResponse('api.user_remove_from_favourites', ['vFullName' => $get_user_full_name->vFullName]);
                    } else {
                        return ErrorResponse('api.user_not_found');
                    }
                }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch(\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Get User Favourite List API
    public function userFavouriteList($request) {
        try {
            $user = Auth::user();
            if($user) {

                #code to append block & reported user ids from emit list
                $arrUserIds = [];
                $blockUserId = BlockUser::getBlockedProfile($user->iUserId);
                $reportedUserId = ReportedUser::getReportedProfile($user->iUserId);

                if (!empty($blockUserId)) {
                    $arrUserIds = array_unique(array_merge($arrUserIds, $blockUserId));
                }

                if (!empty($reportedUserId)) {
                    $arrUserIds = array_unique(array_merge($arrUserIds, $reportedUserId));
                }

                $getFavouriteList = UserFavouriteList::where(['user_favourites_list.iUserId' => $user->iUserId, 'users.tiIsActive' => 1])
                ->select('users.iUserId',DB::raw("CONCAT(vFirstName,' ',vLastName) as vFullName"),DB::raw("TIMESTAMPDIFF(YEAR,DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(0), interval `iDob` second), '%Y-%m-%d'), CURDATE()) as iAge"),'vOccupation','vProfileImage', 'tiIsProfileImageVerified')
                ->leftjoin('users','users.iUserId', '=', 'user_favourites_list.iFavouriteProfileId')
                ->orderBy('user_favourites_list.tsCreatedAt', 'DESC');

                if (!empty($arrUserIds)) {
                    $getFavouriteList = $getFavouriteList->whereNotIn('users.iUserId',[$arrUserIds]);
                }
                $getFavouriteList = $getFavouriteList->get()->toArray();

                if(!empty($getFavouriteList)) {
                    foreach($getFavouriteList as $key => $value) {
                        $getFavouriteList[$key]['vProfileImage'] = !empty($value['vProfileImage']) ? AWSHelper::getCloundFrontUrl($value['vProfileImage'], AWS_USER_PROFILE_IMAGE) : '';
                    }
                    return SuccessResponseWithResult($getFavouriteList,'api.user_favourites_list_success');
                } else {
                    return ErrorResponse('api.user_not_found');
                }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch(\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

}
