<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AWSHelper;
use App\Http\Controllers\Controller;
use App\Models\Api\v1\Device;
use App\Models\Api\v1\User;
use App\Models\Api\v1\UserInterests;
use App\Models\Api\v1\UserLoveLanguages;
use App\Models\Api\v1\UserNotification;
use App\Models\Api\v1\UserPreferences;
use App\Models\Backend\FunInterests;
use App\Models\Backend\LoveLanguages;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use phpDocumentor\Reflection\DocBlock\Tags\See;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userData = User::select('vFirstName', 'vLastName', 'tiGender', 'vEmailId', 'tiIsAdminApproved','tiIsActive', 'vUserUuid','tiIsPremiumUser')->whereNotNull(['tsCreatedAt']);
            // if ($request->order == null) {
            //     $userData->orderBy('tsCreatedAt', 'DESC');
            // }
            return DataTables::of($userData)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if (!is_null($request->get('firstname'))) {
                        $query->where('vFirstName', 'like', "%{$request->get('firstname')}%");
                    }
                    if (!is_null($request->get('lastname'))) {
                        $query->where('vLastName', 'like', "%{$request->get('lastname')}%");
                    }
                    if (!is_null($request->get('email'))) {
                        $query->where('vEmailId', 'like', "%{$request->get('email')}%");
                    }
                    if (!is_null($request->get('gender'))) {
                        if( $request->get('gender') == 1) {
                            $query->where('tiGender','=',1);
                        }
                        if( $request->get('gender') == 2) {
                            $query->where('tiGender','=',2);
                        }
                    }
                    if (!is_null($request->get('status'))) {
                        if( $request->get('status') == 0) {
                            $query->where('tiIsActive','=',0);
                        }
                        if( $request->get('status') == 1) {
                            $query->where('tiIsActive','=',1);
                        }
                    }
                    if (!is_null($request->get('isApproved'))) {
                        if( $request->get('isApproved') == 0) {
                            $query->where('tiIsAdminApproved','=',0);
                        }
                        if( $request->get('isApproved') == 1) {
                            $query->where('tiIsAdminApproved','=',1);
                        }
                    }
                    if (!is_null($request->get('premium'))) {
                        if( $request->get('premium') == 0) {
                            $query->where('tiIsPremiumUser','=',0);
                        }
                        if( $request->get('premium') == 1) {
                            $query->where('tiIsPremiumUser','=',1);
                        }
                    }
                })
                ->editColumn('vEmailId',function($model) {
                    return (is_null($model->vEmailId)) ? 'N/A' : $model->vEmailId;
                })
                ->editColumn('vFirstName',function($model) {
                    return (is_null($model->vFirstName)) ? 'N/A' : $model->vFirstName;
                })
                ->editColumn('vLastName',function($model) {
                    return (is_null($model->vLastName)) ? 'N/A' : $model->vLastName;
                })

                ->editColumn('tiGender', function ($model) {
                    if ($model->tiGender == 1) {
                        return "Male";
                    } elseif ($model->tiGender == 2) {
                        return "Female";
                    } else {
                        return "N/A";
                    }
                })
                ->editColumn('tiIsPremiumUser',function($model) {
                    return ($model->tiIsPremiumUser == 1) ? "Yes" : "No";
                })
                ->addColumn('tiIsAdminApproved', function ($model) {
                    return '<button type="button" vUserUuid="'.$model->vUserUuid.'" isApproved="'.$model->tiIsAdminApproved.'" class="userIsApproved btn btn-block '.($model->tiIsAdminApproved == 1 ? "btn-success" : "btn-danger").' btn-sm w-50" style="margin-top:0px !important;width:80% !important;">'.($model->tiIsAdminApproved == 1 ? "Approved" : "Unapproved").'</button>';
                })
                ->addColumn('status', function ($model) {
                    return '<button type="button" vUserUuid="'.$model->vUserUuid.'" isActive="'.$model->tiIsActive.'" style="margin-top:0px !important;width:80% !important;" id="btnStatus" class="btn btn-block '.($model->tiIsActive == 1 ? "btn-success" : "btn-danger").' btn-sm w-50"> '.($model->tiIsActive == 1 ? "Active" : "Inactive").'</button>';
                })

                ->addColumn('action', function ($model) {
                    return
                        '<a href="' . route("backend.user_view", ['vUserUuid' => $model->vUserUuid]) . ' " class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a> <a href="' . route("backend.user_edit", ['vUserUuid' => $model->vUserUuid]) . ' " class="btn btn-info btn-xs" ><i class="fa fa-edit"></i></a> <a href="' . route("backend.user_delete", ['vUserUuid' => $model->vUserUuid]) . ' " class="btn btn-danger btn-xs "  onClick="return confirm(\'Are you sure ?\')"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['tiIsAdminApproved', 'action','status'])
                ->make(true);
        }
        $request_data=[
            'gender' => $request->dashboardThroughGender ?? '',
            'users' => $request->users ?? '',
            'activeUsers' => $request->activeUsers ?? '',
            'inactiveUsers' => $request->inactiveUsers ?? '',
            'premiumUsers' => $request->premiumUsers ?? '',
            'basicUsers' => $request->basicUsers ?? '',
            'unapprovedUsers' => $request->unapprovedUsers ?? ''
        ];
        return view('backend.users.index',compact('request_data'));
    }

    public function adminApprovedStatus(Request $request)
    {
        if ($request->ajax()) {
            $uuid = $request->vUserUuid;
            $status = $request->tiIsAdminApproved;
            $new_status = ($status == 1) ? 0 : 1;
            $data = User::select('iUserId')->where('vUserUuid', $uuid)->first();
            $data->tiIsAdminApproved = $new_status;
            if(empty($data)) {
                return response()->json(['status'=>false ,'msg'=>'User not found.']);
            }
            if ($data->save()) {
                $NotificationData = [
                    'title' => 'Hey,Your request approved.',
                    'body' => 'Now you can explore our app.',
                    'badge' => 0,
                    'sound' => 'default',
                    'icon' => asset('theme/dist/img/logo.png'),
                    'type' => '1'
                ];
                $adminNotificationData = [
                    'iRecievedUserId' => $data->iUserId,
                    'iSendUserId' => null,
                    'vNotificationTitle' => 'Congratulations your request approved.',
                    'vNotificationDesc' => 'Now you can explore our app.',
                    'tiNotificationType' => '3',
                ];
                UserNotification::create($adminNotificationData);
                $token = Device::where('iUserId','=',$data->iUserId)->where('tiDeviceType','=',1)->get(['vDeviceToken'])->first();
                if(!empty($token)) {
                        pushCurlCall($token->vDeviceToken, $NotificationData);
                        return response()->json(['status' => true, 'msg' => 'User approved successfully.', ]);
                    }
                return response()->json(['status' => true, 'msg' => 'User approved successfully.', ]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Somthing went wrong while update status.']);
            }
        }
    }
    public function approveAllUsers(Request $request) {
        if($request->ajax()){

            $userList = User::select('users.iUserId','vUserUuid','vDeviceToken')->where('tiIsAdminApproved',0)->leftjoin('devices','devices.iUserId','users.iUserId')->get()->toArray();
            if(!empty($userList)) {
                $tokens = [];
                $useruuid = [];
                $adminNotificationData = [];
                foreach($userList as $user) {
                    $adminNotificationData[] = [
                        'iRecievedUserId' => $user['iUserId'],
                        'iSendUserId' => null,
                        'vNotificationTitle' => 'Congratulations your request approved.',
                        'vNotificationDesc' => 'Now you can explore our app.',
                        'tiNotificationType' => '3',
                    ];
                    if(!is_null($user['vDeviceToken']))
                        $tokens[] = $user['vDeviceToken'];
                    $useruuid[] = $user['vUserUuid'];
                }
                $data['data'] = [
                    'title' => 'Congratulations your request approved.',
                    'body' => 'Now you can explore our app.',
                    'badge' => 0,
                    'sound' => 'default',
                    'icon' => asset('theme/dist/img/logo.png'),
                    'type' => '1'
                ];
                if(!empty($adminNotificationData)) {
                    UserNotification::insert($adminNotificationData);
                }
                if(!empty($useruuid)) {
                    User::whereIn('vUserUuid',$useruuid)->update(['tiIsAdminApproved'=>1]);
                }
                Session::flash('success','All users approved successfully');
                pushCurlCall($tokens,$data);
            } else {
                Session::flash('success','All users already approved');
            }
            return 1;
        }
    }
    public function checkStatus(Request $request)
    {
        if($request->ajax()) {
            $uuid = $request->vUserUuid;
            $status = $request->tiIsActive;
            $new_status = ($status == 1) ? 0 :1 ;
            $data = User::select('iUserId')->where('vUserUuid',$uuid)->first();
            if(empty($data)) {
                return response()->json(['status'=>false ,'msg'=>'user not found.']);
            }
            $data->tiIsActive = $new_status;
            if($data->save()) {
                if($new_status==0) {

                    $token = Device::where('iUserId',$data->iUserId)->select('vDeviceToken')->first();
                    if(!empty($token)) {
                        $data['data'] = [
                            'title' => 'Your account is deactivated.',
                            'body' => 'Please contact admin for activate account.',
                            'badge' => 0,
                            'sound' => 'default',
                            'icon' => asset('theme/dist/img/logo.png'),
                            'type' => '1'
                        ];
                        pushCurlCall($token->vDeviceToken,$data);
                    }
                    Device::where('iUserId',$data->iUserId)->delete();
                    PersonalAccessToken::where('tokenable_id',$data->iUserId)->delete();
                }
                return response()->json(['status'=>true,'msg'=>'Status changed successfully.','new_status' => $new_status]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Somthing went wrong while update status.']);
            }
        }
    }

    public function userView($vUserUuid)
    {   $user = User::select('users.*','user_subscriptions.dtSubscriptionStartDate','user_subscriptions.dtSubscriptionEndDate','up.tiSameEthnicity','up.tiSameNationality','up.tiSameNationality','up.vPreferredEarnings','up.tiIsDrinkingPreferred','up.tiIsDrugPreferred','up.tiPreferredPreviouslyMarried','up.tiLikeToHaveKids','up.tiPreferredEducation','up.tiPreferredAge','up.tiPreferredReligiousBeliefs')
                ->where('vUserUuid',$vUserUuid)
                ->leftJoin('user_preferences as up','up.iUserId','users.iUserId')
                ->leftjoin('transaction','users.iUserId','transaction.iUserId')
                ->leftjoin('user_subscriptions','user_subscriptions.iUserId','user_subscriptions.iUserId')
                ->first();

        $UserInterests = UserInterests::select(DB::raw('group_concat(fun_interests.vInterestName) as interest'))
                        ->where('iUserId',$user->iUserId)
                        ->leftJoin('fun_interests',function($join) {
                            $join->on('fun_interests.iInterestId','user_interests.iInterestId');
                        })->first();
        $userLoveLanguage = UserLoveLanguages::select(DB::raw('group_concat(love_languages.vLoveLanguage) as loveLanguage'))
                        ->where('iUserid',$user->iUserId)
                        ->leftJoin('love_languages',function($join) {
                            $join->on('love_languages.iLoveLanguageId','user_love_languages.iLoveLanguageId');
                        })->first();

        $profile_url = (!empty($user->vProfileImage)) ? AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE) : '';
        $selfie_url = (!empty($user->vSelfieImage)) ? AWSHelper::getCloundFrontUrl($user->vSelfieImage, AWS_USER_SELFIE_IMAGE) : '';
        $video_url = (!empty($user->vVideo)) ? AWSHelper::getCloundFrontUrl($user->vVideo, AWS_USER_VERIFICATION_VIDEO) : '';
        return view('backend.users.view',compact('user','profile_url','selfie_url','video_url','userLoveLanguage','UserInterests'));
    }

    public function userEdit($vUserUuid)
    {
        $user = User::select('users.*','user_subscriptions.dtSubscriptionStartDate','user_subscriptions.dtSubscriptionEndDate')
        ->where('vUserUuid', $vUserUuid)
        ->leftJoin('user_preferences','user_preferences.iUserId','users.iUserId')
        ->leftjoin('user_subscriptions','user_subscriptions.iUserId','user_subscriptions.iUserId')
        ->first();
        // $allInterests = FunInterests::select('vInterestName')->get()->toArray();
        // $UserInterests = UserInterests::select('vInterestName')
        //                 ->leftJoin('fun_interests','fun_interests.iInterestId','user_interests.iInterestId')
        //                 ->where('iUserId',$user->iUserId)
        //                 ->get()->toArray();
        // $UserInterestsArray = [];
        // foreach ($UserInterests as $value) {
        //     $UserInterestsArray[] = $value['vInterestName'];
        // }
        // $userLoveLanguages = UserLoveLanguages::select('vLoveLanguage')
        //                     ->leftJoin('love_languages','love_languages.iLoveLanguageId','user_love_languages.iLoveLanguageId')
        //                     ->where('iUserId',$user->iUserId)
        //                     ->get()->toArray();
        // $userLoveLanguagesArray = [];
        // foreach ($userLoveLanguages as $value) {
        //     $userLoveLanguagesArray[] = $value['vLoveLanguage'];
        // }
        // $allLoveLanguages = LoveLanguages::select('vLoveLanguage')->get()->toArray();
        // return view('backend.users.edit', compact('user','allInterests','UserInterestsArray','allLoveLanguages','userLoveLanguagesArray','profile_url','selfie_url','video_url'));
        $UserInterests = UserInterests::select(DB::raw('group_concat(fun_interests.vInterestName) as interest'))
                        ->where('iUserId',$user->iUserId)
                        ->leftJoin('fun_interests',function($join) {
                            $join->on('fun_interests.iInterestId','user_interests.iInterestId');
                        })->first();
        $countries = Country::select('vDialingCode')->where('vDialingCode','!=','')->get()->toArray();
        $userLoveLanguage = UserLoveLanguages::select(DB::raw('group_concat(love_languages.vLoveLanguage) as loveLanguage'))
                        ->where('iUserId',$user->iUserId)
                        ->leftJoin('love_languages',function($join) {
                            $join->on('love_languages.iLoveLanguageId','user_love_languages.iLoveLanguageId');
                        })->first();
        $profile_url = (!empty($user->vProfileImage)) ? AWSHelper::getCloundFrontUrl($user->vProfileImage, AWS_USER_PROFILE_IMAGE) : '';
        $selfie_url = (!empty($user->vSelfieImage)) ? AWSHelper::getCloundFrontUrl($user->vSelfieImage, AWS_USER_SELFIE_IMAGE) : '';
        $video_url = (!empty($user->vVideo)) ? AWSHelper::getCloundFrontUrl($user->vVideo, AWS_USER_VERIFICATION_VIDEO) : '';
        return view('backend.users.edit', compact('user','UserInterests','countries','userLoveLanguage','profile_url','selfie_url','video_url'));
    }

    public function userDelete(Request $request, $vUserUuid)
    {
        $user = User::where('vUserUuid', $vUserUuid)->first();
        if ($user->delete()) {
            $request->session()->flash('success', 'User Delete successfully.');
            return redirect()->route('backend.manage-users.index');
        } else {
            $request->session()->flash('error', ' Failed to delete User.');
        }
        return redirect()->back();
    }

    public function userUpdate(Request $request,$vUserUuid)
    {
        $request->validate([
            'vFirstName' => 'nullable|alpha',
            'vLastName' => 'nullable|alpha',
            'vEmailId' => 'nullable|max:35|min:5|regex:/^([a-zA-Z0-9\+_\-]+)(\.[a-zA-Z0-9\+_\-]+)*@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/',
            'vMobileNumber' => 'nullable|max:12|min:5|regex:/^[0-9\+]+$/',
            'vISDCode' => 'nullable|required_if:vMobileNumber,!null'
            // 'vZodiacSignName' => 'nullable|alpha',
            // 'vOriginCountry' => 'nullable|alpha',
            // 'vLivingCountry' => 'nullable|alpha',
            // 'vCity' => 'nullable|alpha',
            // 'vFaith' => 'nullable|alpha',
            // 'vOccupation' => 'nullable|alpha',
            // 'vEarnings'=> 'required',
            // 'vProfileImage' => 'nullable|mimes:png,jpg,jpeg',
            // 'vSelfieImage' => 'nullable|mimes:png,jpg,jpeg'
        ],[
            'vFirstName.alpha' => 'First name must be string.',
            'vLastName.alpha' => 'Last name must be string.',
            'vZodiacSignName.alpha' => 'Zodiac sign name must be string.',
            'vOriginCountry.alpha' => 'Origin country name must be string.',
            'vLivingCountry.alpha' => 'Living country name must be string.',
            'vEmailId.regex' => 'Please give proper email id.',
            'vCity.alpha' => 'City name must be string.',
            'vOccupation.alpha' => 'Occupation must be string.',
            'vISDCode.required_id' => 'Please select ISD code.'
            // 'vProfileImage.mimes' => 'Please select only .png, .jpeg, .jpg file.'
        ]);
        $user = User::where('vUserUuid', $vUserUuid)->first();
        // if (!empty($user->userPreferences)) {
        //     $user_preference = $user->userPreferences;
        // } else {
        //     $user_preference = new UserPreferences();
        //     $user_preference->iUserId = $user->iUserId;
        // }
        // $user_preference->tiSameEthnicity = !empty($request->tiSameEthnicity) ? $request->tiSameEthnicity : $user_preference->tiSameEthnicity ;
        // $user_preference->tiSameNationality = !empty($request->tiSameNationality) ? $request->tiSameNationality : $user_preference->tiSameNationality ;
        // $user_preference->vPreferredEarnings = !empty($request->vPreferredEarnings) ? $request->vPreferredEarnings : $user_preference->vPreferredEarnings ;
        // $user_preference->tiIsDrinkingPreferred = !empty($request->tiIsDrinkingPreferred) ? $request->tiIsDrinkingPreferred : $user_preference->tiIsDrinkingPreferred ;
        // $user_preference->tiIsDrugPreferred = !empty($request->tiIsDrugPreferred) ? $request->tiIsDrugPreferred : $user_preference->tiIsDrugPreferred ;
        // $user_preference->tiPreferredPreviouslyMarried = !empty($request->tiPreferredPreviouslyMarried) ? $request->tiPreferredPreviouslyMarried : $user_preference->tiPreferredPreviouslyMarried ;
        // $user_preference->tiLikeToHaveKids = !empty($request->tiLikeToHaveKids) ? $request->tiLikeToHaveKids : $user_preference->tiLikeToHaveKids ;
        // $user_preference->tiPreferredEducation = !empty($request->tiPreferredEducation) ? $request->tiPreferredEducation : $user_preference->tiPreferredEducation ;
        // $user_preference->tiPreferredAge = !empty($request->tiPreferredAge) ? $request->tiPreferredAge : $user_preference->tiPreferredAge ;
        // $user_preference->tiPreferredReligiousBeliefs = !empty($request->tiPreferredReligiousBeliefs) ? $request->tiPreferredReligiousBeliefs : $user_preference->tiPreferredReligiousBeliefs ;
        $user->vFirstName = empty($request->vFirstName) ? null : $request->vFirstName;
        $user->vLastName = empty($request->vLastName) ? null : $request->vLastName;
        $user->vISDCode = empty($request->vISDCode) ? null : $request->vISDCode;
        $user->vEmailId = empty($request->vEmailId) ? null : $request->vEmailId;
        $user->vISDCode =empty($request->vISDCode) ? null : $request->vISDCode;
        $user->vMobileNumber = empty($request->vMobileNumber) ? null : $request->vMobileNumber;
        $user->tiIsActive = $request->tiIsActive;
        if($user->tiIsAdminApproved == 0)
            $user->tiIsAdminApproved = $request->tiIsAdminApproved;
        // $user->vZodiacSignName = empty($request->vZodiacSignName) ? null : $request->vZodiacSignName;
        // $user->vOriginCountry = empty($request->vOriginCountry) ? null : $request->vOriginCountry;
        // $user->vEthnicGroup = empty($request->vEthnicGroup) ? null : $request->vEthnicGroup;
        // $user->vLivingCountry = empty($request->vLivingCountry) ? null : $request->vLivingCountry;
        // $user->vCity = empty($request->vCity) ? null : $request->vCity;
        // $user->vFaith = empty($request->vFaith) ? null : $request->vFaith;
        // $user->vOccupation = empty($request->vOccupation) ? null : $request->vOccupation;
        // $user->vEarnings = empty($request->vEarnings) ? null : $request->vEarnings;
        // $user->tiLookingFor = ($request->tiLookingFor == 0) ? null : $request->tiLookingFor;
        // $user->tiRelationshipStatus = ($request->tiRelationshipStatus == 0) ? null : $request->tiRelationshipStatus;
        // $user->txAboutYourSelf = $request->txAboutYourSelf;
        // $user->txDealBreaker = $request->txDealBreaker;
        // // $user->vProfileImage = empty($request->vProfileImage) ? $user->vProfileImage : $request->vProfileImage;
        // // $user->vSelfieImage = empty($request->vSelfieImage) ? $user->vSelfieImage : $request->vSelfieImage;
        // $user->iDob = empty($request->iDob) ? null : strtotime($request->iDob);
        // $user->tiIsDrink = ($request->tiIsDrink == 0) ? null : $request->tiIsDrink;
        // $user->tiUseDrugs = ($request->tiUseDrugs == 0) ? null : $request->tiUseDrugs;
        // $user->tiBelieveInMarriage = $request->tiBelieveInMarriage;
        // $user->tiHaveKids = $request->tiHaveKids;
        // $user->vEducationQualification = $request->vEducationQualification;


        // if(!empty($request->vInterestNames)) {
        //     UserInterests::where('iUserId',$user->iUserId)->delete();
        //     $interestArr = [];
        //     foreach ($request->vInterestNames as $key => $interest) {
        //         $interestArr['iUserId'] = $user->iUserId;
        //         $interestArr['iInterestId'] = $interest;
        //         UserInterests::insert($interestArr);
        //     }
        // }
        // if(!empty($request->vLoveLanguages)) {
        //     UserLoveLanguages::where('iUserId',$user->iUserId)->delete();
        //     $loveLangArr = [];
        //     foreach ($request->vLoveLanguages as $key => $lang) {
        //         $loveLangArr['iUserId'] = $user->iUserId;
        //         $loveLangArr['iLoveLanguageId'] = $lang;
        //         UserLoveLanguages::insert($loveLangArr);
        //     }
        // }
        if($user->save()){
            if(($user->tiIsAdminApproved != $request->tiIsAdminApproved) && ( $user->tiIsAdminApproved == 0 )) {
                $data['data'] = [
                    'title' => 'Notification',
                    'body' => ($request->tiIsAdminApproved == 1) ? 'Congratulations admin approved you.' : 'Admin cancel your request for access the app.',
                    'badge' => 0,
                    'sound' => 'default',
                    'icon' => asset('theme/dist/img/logo.png'),
                ];
                $device = Device::select('vDeviceToken')->where(['iUserId'=>$user->iUserId])->first()->toArray();
                if(!empty($device) && !is_null($device['vDeviceToken'])) {
                    $res = pushCurlCall($device['vDeviceToken'], $data);
                }
            }
            // $user_preference->save();
            $request->session()->flash('success', ' User Update successfully.');
            return redirect()->route('backend.manage-users.index');
        }else{
            $request->session()->flash('error', ' Failed to Update user.');
        }
        return redirect()->back();
    }
}
