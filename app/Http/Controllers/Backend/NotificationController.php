<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\Device;
use App\Models\Api\v1\UserNotification;
use App\Models\Backend\User;
use App\Models\Backend\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    public function index()
    {
        return view('backend.notification.index');
    }
    public function sendNotification(Request $request) {

        $request->validate([
            'vTitle' => 'required',
            'vMessage' => 'required|min:10',
            'iType' => 'required'
        ],[
            'vTitle.required' => 'Title is Required',
            'vMessage.required' => 'Message is Required',
            'vMessage.min' => 'Message must be 10 character long.',
            'iType.required' => 'Type is required'
        ]);
        $data['data'] = [
            'title' => $request->vTitle,
            'body' => $request->vMessage,
            'badge' => 0,
            'sound' => 'default',
            'icon' => asset('theme/dist/img/logo.png'),
        ];
        $users = User::select('users.iUserId','vDeviceToken')->where(['tiIsActive'=>1,'tiIsAdminApproved'=>1]);
        if($request->iType==2) {
            $users->where('tiIsPremiumUser','=',0);
        }
        $userList = $users->leftJoin('devices','devices.iUserId','users.iUserId')->get()->toArray();
        if(!empty($userList)) {
            $tokens = [];
            $adminNotificationData = [];
            foreach($userList as $user) {
                $adminNotificationData[] = [
                    'iRecievedUserId' => $user['iUserId'],
                    'iSendUserId' => null,
                    'vNotificationTitle' => $request->vTitle,
                    'vNotificationDesc' => $request->vMessage,
                    'tiNotificationType' => '3',
                ];
                if(!empty($user['vDeviceToken']))
                    $tokens[] = $user['vDeviceToken'];
            }
            if(!empty($adminNotificationData)) {
                UserNotification::insert($adminNotificationData);
            }
            if(!empty($tokens)) {
                    $res = pushCurlCall($tokens, $data);
                    Session::flash('success','Notification send successfully');
                    return redirect()->back();
            }
        }
        Session::flash('error','No user found.');
        return redirect()->back();
    }
    public function updateVersion() {
        ini_set('memory_limit','-1');
        $versionObj = Version::select('fVersion')->first()->toArray();
        $version = $versionObj['fVersion'];
        return view('backend.notification.updateVersion',compact('version'));
    }
    public function saveVersion(Request $request)
    {
        $versionObj = Version::select('fVersion','iVersionId')->first();
        if($request->fVersion <= $versionObj->fVersion) {
            Session::flash('error','Please check version you have entered.It should be greater than then previous version');
            return redirect()->back();
        }
        $data['data'] = [
            'title' => 'New app update',
            'body' => $request->vMessage ?? 'New updates available.Please update pora app.',
            'badge' => 0,
            'sound' => 'default',
            'icon' => asset('theme/dist/img/logo.png'),
        ];
        $userList = User::select('vDeviceToken')->where(['tiIsActive'=>1,'tiIsAdminApproved'=>1])->leftJoin('devices','devices.iUserId','users.iUserId')->get()->toArray();
        if(!empty($userList)) {
            $tokens = [];
            foreach($userList as $user) {
                if(!empty($user['vDeviceToken']))
                    $tokens[] = $user['vDeviceToken'];
            }
            if(!empty($tokens)) {
                    $res = pushCurlCall($tokens, $data);
                    $versionObj->fVersion = $request->fVersion;
                    if($versionObj->save()) {
                        Session::flash('success','New version update notification send successfully');
                    } else {
                        Session::flash('error','Something went wrong.Please try again.');
                    }
            } else {
                Session::flash('success','New version update notification send successfully.');
            }
            $versionObj->fVersion = $request->fVersion;
            $versionObj->save();
            return redirect()->back();
        }
        Session::flash('error','No user found.');
        return redirect()->back();
    }
}
