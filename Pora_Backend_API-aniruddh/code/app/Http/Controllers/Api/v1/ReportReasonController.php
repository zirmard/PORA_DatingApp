<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Api\v1\ReportedUser;
use App\Models\Api\v1\User;
use App\Models\Api\v1\UserFavouriteList;
use App\Models\Api\v1\UserLike;
use App\Models\Backend\ReportReason;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportReasonController extends BaseController
{
    public function reportReasons() { // Report Reason List API
        try {
            $result = ReportReason::where(['tiIsActive' =>1])->select('iReportReasonId','vReportReason')->get()->toArray();

            if(!empty($result)) {
                return SuccessResponseWithResult($result,"api.report_reason");
            } else {
                return ErrorResponse("api.report_reason_not_found");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Create Report Reason API
    public function createReportReason(Request $request) {
        try {
            $user = Auth::user();
            if($user) {
                $isExists = ReportedUser::where(['iUserId' => $user->iUserId, 'iReportedUserId' => $request->iReportedUserId])->exists();
                if($isExists) {
                    return ErrorResponse('api.user_already_reported');
                }
                $reportedUser = new ReportedUser();
                $reportedUser->vUserReportUuid = Str::uuid()->toString();
                $reportedUser->iReportReasonId = $request->iReportReasonId;
                $reportedUser->iUserId = $user->iUserId;
                $reportedUser->iReportedUserId = $request->iReportedUserId;
                $reportedUser->txDetails = $request->txDetails;
                $reportedUser->save();

                # Delete the user that is going to be reported from the user_likes, if exists
                $getLikedUser = UserLike::where(['iUserId' => $user->iUserId, 'iLikeUserId' => $request->iReportedUserId])->first();
                if(!empty($getLikedUser)) {
                    $getLikedUser->delete();
                }

                # Delete the user that is going to be reported from the user_favourites_list, if exists
                $getFavouriteUser = UserFavouriteList::where(['iUserId' => $user->iUserId, 'iFavouriteProfileId' => $request->iReportedUserId])->first();
                if(!empty($getFavouriteUser)) {
                    $getFavouriteUser->delete();
                }

                $result = [
                    'iReportReasonId' => $reportedUser->iReportReasonId,
                    'iUserId' => $reportedUser->iUserId,
                    'iReportedUserId' => $reportedUser->iReportedUserId,
                    'txDetails' => $reportedUser->txDetails
                ];

                $reportedUserName = User::select(DB::raw("CONCAT(vFirstName,' ',vLastName) as vFullName"))->where(['iUserId' => $request->iReportedUserId])->first();

                # Email the reported user details to admin
                $emailData = [
                    'reportedByUserName' => $user->vFirstName.' '.$user->vLastName,
                    'reportedUserName' => $reportedUserName->vFullName,
                    'reportReason' => $request->txDetails,
                ];
                $param['from'] = env('MAIL_FROM_ADDRESS');
                $param['to'] =  env('MAIL_FROM_ADDRESS');
                $param['filename'] = 'emails.report-user';
                $param['subject'] = 'Report Reason';
                sendEmail($emailData, $param);
                # Email Ends Here
                return SuccessResponseWithResult($result,"api.user_reprort_reason_success", ['vFullName' => $reportedUserName->vFullName]);
            } else {
                return ErrorResponse("api.user_not_found");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }
}
