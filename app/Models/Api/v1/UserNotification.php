<?php

namespace App\Models\Api\v1;

use App\Helpers\AWSHelper;
use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserNotification extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'user_notifications';
    protected $primaryKey = 'iUserNotificationId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iRecievedUserId',
        'iSendUserId',
        'vNotificationTitle',
        'vNotificationDesc',
        'tiNotificationType',
        'iNotificationTimeExprie',
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
        'tiIsRead' => 0,
        'tiIsActive' => 1
    ];

    # Get User Notifications List API
    public function userNotificationsList($request) {
        try {
            $user = Auth::user();
            if($user) {
                // if($user->tiIsActive == 1) {
                $getNotifications = self::where(['iRecievedUserId' => $user->iUserId, 'user_notifications.tiIsActive' => 1])
                ->select(
                    'vNotificationTitle','vNotificationDesc',
                    DB::raw("CONCAT(vFirstName,' ',vLastName) as vFullName"),
                    DB::raw("TIMESTAMPDIFF(YEAR,DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(0), interval `iDob` second), '%Y-%m-%d'), CURDATE()) as iAge"),
                    'vProfileImage',
                    'tiIsProfileImageVerified',
                    'user_notifications.iSendUserId',
                    'user_notifications.iRecievedUserId',
                    'user_notifications.tsCreatedAt'
                    )
                ->leftjoin('users', 'users.iUserId', '=', 'user_notifications.iSendUserId')
                ->orderBy('user_notifications.tsCreatedAt', 'DESC');

                # Check if User is Premium or not. If not, then disappear like notification after 5 hours, else disappear after 72 hours.
                if($user->tiIsPremiumUser == "0") {
                    $getNotifications->where(DB::raw("user_notifications.tsCreatedAt + INTERVAL 72 HOUR"), '>=', now());
                } else {
                    $getNotifications->where(DB::raw("user_notifications.tsCreatedAt + INTERVAL 72 HOUR"), '>=', now());
                }
                # Ends Here.
                $getNotifications = $getNotifications->get();

                if($getNotifications->isNotEmpty()) {
                    return SuccessResponseWithResult($this->getNotificationsResponse($getNotifications), 'api.get_notifications_success');
                } else {
                    return ErrorResponse('api.user_not_found');
                }
                // }
                // else {
                //     return ErrorResponse('api.unauthenticated');
                // }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch(\Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Get User Notifications Response
    public function getNotificationsResponse($getNotifications) {
        # if iSendUserId is null then admin notification else user notification
        foreach($getNotifications as $notification) {
            $notificationResponse [] = [
                'vNotificationTitle' => $notification->vNotificationTitle,
                'vNotificationDesc' => $notification->vNotificationDesc,
                'iSendUserId' => $notification->iSendUserId ,
                'iRecievedUserId' => $notification->iRecievedUserId,
                'vFullName' => $notification->vFullName,
                'iAge' => $notification->iAge,
                'vProfileImage' => !empty($notification->vProfileImage) ? AWSHelper::getCloundFrontUrl($notification->vProfileImage,AWS_USER_PROFILE_IMAGE) : '',
                'tiIsProfileImageVerified' => $notification->tiIsProfileImageVerified,
                'tsCreatedAt' => TimeElapsedString($notification->tsCreatedAt)
            ];
        }
        return $notificationResponse;
    }
}
