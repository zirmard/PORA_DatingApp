<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Api\v1\User;
use App\Models\Api\v1\UserContactReason;
use App\Models\Backend\Admin;
use App\Models\Backend\ContactReason;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactReasonController extends BaseController
{
    # Contact Reason List API
    public function getContactReasons() {
        try {
            $result = ContactReason::where(['tiIsActive' =>1])->select('iContactReasonId','vContactReason')->get()->toArray();

            if(!empty($result)) {
                return SuccessResponseWithResult($result,"api.contact_reason");
            } else {
                return ErrorResponse("api.contact_reason_not_found");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # Contact Reason Create API
    public function createContactReason(Request $request) {
        try {
            $user = Auth::user();
            if($user) {
                $adminEmail = Admin::select('vEmail')->where(['tiIsActive' => 1])->first();
                $contactReason = new UserContactReason();
                $contactReason->iUserId = $user->iUserId;
                $contactReason->iContactReasonId = $request->iContactReasonId;
                $contactReason->txDetails = $request->txDetails;
                if($contactReason->save()) {
                    # Send Email to Admin
                    $getContactReason = ContactReason::select('vContactReason')->where(['iContactReasonId' => $contactReason->iContactReasonId ])->first();
                    $getUserDetails = User::select('vFirstName','vLastName','vEmailId')->where(['iUserId' => $contactReason->iUserId, 'tiIsActive' => 1])->first();
                    if(!empty($getContactReason)) {
                        $vReason = $getContactReason->vContactReason;
                    }
                    $emailData = ['txDetails' => $contactReason->txDetails, 'vReason' => $vReason, 'vFullName' => $getUserDetails->vFirstName.' '.$getUserDetails->vLastName, 'vEmailId' => $getUserDetails->vEmailId];
                    $param['from'] = env('MAIL_FROM_ADDRESS');
                    $param['to'] = $adminEmail->vEmail;
                    $param['filename'] = 'emails.contact-us';
                    $param['subject'] = 'Contact Reason';
                    sendEmail($emailData,$param);
                }
                $result = [
                    'iUserId' => $contactReason->iUserId,
                    'iContactReasonId' => $contactReason->iContactReasonId,
                    'txDetails' => $contactReason->txDetails
                ];
                return SuccessResponseWithResult($result,"api.user_contact_reason_success");
            } else {
                return ErrorResponse("api.user_not_found");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }
}
