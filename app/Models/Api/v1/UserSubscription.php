<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserSubscription extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'user_subscriptions';
    protected $primaryKey = 'iUserSubscriptionId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iUserId',
        'iSubscriptionPlanId',
        'vProductId',
        'tiIsAutoRenewing',
        'txProductMetaData',
        'ltxSubscriptionToken',
        'dtSubscriptionStartDate',
        'dtSubscriptionEndDate',
        'txError',
        'tiIsSandbox',
        'tiIsActive',
        'tsDeletedAt',
        'tsCreatedAt',
        'tsUpdatedAt',
        'vPackageName',
        'vOrderId',
    ];

    public function updateSubscription($request)
    {
        // $request->validate([
        //     'vProductId' => 'required',
        //     'vPackageName' => 'required',
        //     'vOrderId' => 'required',
        //     'ltxSubscriptionToken' => 'required',
        // ]);
        // try {
        //     $user = Auth::user();
        //     $subscriptionData = [
        //         'iUserId' => $user->iUserId,
        //         'vProductId' => $request->vProductId,
        //         'vPackageName' => $request->vPackageName,
        //         'vOrderId' => $request->vOrderId,
        //         'ltxSubscriptionToken' => $request->ltxSubscriptionToken,
        //     ];
        //     $result = AndroidVerifySubscription($subscriptionData);
        //     if ($result['status'] == false) {
        //         return ErrorResponse('Invalid subscription');
        //     } else {
        //         $dtSubscriptionStartDate = $result['data']['startTimeMillis'];
        //         $tiIsAutoRenewing = $result['data']['autoRenewing'];
        //         $dtSubscriptionEndDate = $result['data']['expiryTimeMillis'];
        //         $subscriptionData['dtSubscriptionStartDate'] = $dtSubscriptionStartDate;
        //         $subscriptionData['tiIsAutoRenewing'] = $tiIsAutoRenewing;
        //         $subscriptionData['dtSubscriptionEndDate'] = $dtSubscriptionEndDate;
        //     }
        //     if (UserSubscription::create($subscriptionData)) {
        //         $premiumUser = User::where('iUserId', $user->iUserId)->update(['tiIsPremiumUser' => 1]);
        //     }
        //     $IsPremiumUser = User::select('tiIsPremiumUser')->where('iUserId', $user->iUserId)->first();
        //     $thisPremiumUser = $IsPremiumUser['tiIsPremiumUser'];
        //     $subscriptionData['tiIsPremiumUser'] = (int) $thisPremiumUser;

        //     if ($IsPremiumUser->tiIsPremiumUser == 1) {
        //         $premium = User::select('users.iUserId','vDeviceToken')
        //             ->leftJoin('devices', 'users.iUserId', '=', 'devices.iUserId')
        //             ->where('users.iUserId',$user->iUserId)->get();

        //         // print_r($premium->toArray()); die;
        //         if (!empty($premium)) {
        //             $device = [];
        //             foreach ($premium as $devices) {
        //                 if (!empty($devices->vDeviceToken)) {
        //                     $device[] = $devices->vDeviceToken;
        //                 }
        //             }
        //             $fields['data'] = [
        //                 'title' => 'Pora',
        //                 'body' => 'Subscription successfully',
        //                 'badge' => 'badge',
        //                 'sound' => 'default',
        //                 'icon' => asset('theme/dist/img/logo.png'),
        //             ];
        //             // dd($device);
        //             $push = pushCurlCall($device, $fields);
        //         }
        //     }
        //     return SuccessResponseWithResult($subscriptionData, "Subscription successfully");
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        // }

        #new flow in app

        $request->validate([
            'vProductId' => 'required',
            'vPackageName' => 'required',
            'vOrderId' => 'required',
            'ltxSubscriptionToken' => 'required',
        ]);
        try {
            $user = Auth::user();
            $subscriptionData = [
                'iUserId' => $user->iUserId,
                'vProductId' => $request->vProductId,
                'vPackageName' => $request->vPackageName,
                'vOrderId' => $request->vOrderId,
                'ltxSubscriptionToken' => $request->ltxSubscriptionToken,
            ];
            $result = AndroidVerifySubscription($subscriptionData);
            // echo "<pre>";
            // print_r($result); die;
            if ($result['status'] == false) {
                return ErrorResponse('Invalid subscription');
            } else {
                $isExists = UserSubscription::select('iUserId', 'vProductId', 'vPackageName', 'vOrderId', 'ltxSubscriptionToken', 'dtSubscriptionStartDate', 'tiIsAutoRenewing', 'dtSubscriptionEndDate')->where('iUserId', $user->iUserId)->first();
                if (!empty($isExists)) {
                    $update = UserSubscription::where('iUserId', $user->iUserId)
                        ->update([
                            'vProductId' => $request['vProductId'],
                            'vPackageName' => $request['vPackageName'],
                            'vOrderId' => $request['vOrderId'],
                            'ltxSubscriptionToken' => $request['ltxSubscriptionToken'],
                            'dtSubscriptionStartDate' => $result['data']['startTimeMillis'],
                            'tiIsAutoRenewing' => $result['data']['autoRenewing'],
                            'dtSubscriptionEndDate' => $result['data']['expiryTimeMillis']
                        ]);
                    $subscriptionData['dtSubscriptionStartDate'] = $result['data']['startTimeMillis'];
                    $subscriptionData['tiIsAutoRenewing'] = $result['data']['autoRenewing'];
                    $subscriptionData['dtSubscriptionEndDate'] = $result['data']['expiryTimeMillis'];
                    User::where('iUserId', $user->iUserId)->update(['tiIsPremiumUser' => 1]);
                    $subscriptionData['tiIsPremiumUser'] = 1;
                    return SuccessResponseWithResult($subscriptionData, "Update successfully");
                } else {
                    $dtSubscriptionStartDate = $result['data']['startTimeMillis'];
                    $tiIsAutoRenewing = $result['data']['autoRenewing'];
                    $dtSubscriptionEndDate = $result['data']['expiryTimeMillis'];
                    $subscriptionData['dtSubscriptionStartDate'] = $dtSubscriptionStartDate;
                    $subscriptionData['tiIsAutoRenewing'] = $tiIsAutoRenewing;
                    $subscriptionData['dtSubscriptionEndDate'] = $dtSubscriptionEndDate;
                    $create = UserSubscription::create($subscriptionData);
                    if ($create == true) {
                        $premiumUser = User::where('iUserId', $user->iUserId)->update(['tiIsPremiumUser' => 1]);
                    }
                    $subscriptionData['tiIsPremiumUser'] = 1;
                    return SuccessResponseWithResult($subscriptionData, "Subscription successfully");
                }
            }
            $IsPremiumUser = User::select('tiIsPremiumUser')->where('iUserId', $user->iUserId)->first();
            $thisPremiumUser = $IsPremiumUser['tiIsPremiumUser'];
            $subscriptionData['tiIsPremiumUser'] = (int) $thisPremiumUser;

            if ($IsPremiumUser->tiIsPremiumUser == 1) {
                $premium = User::select('users.iUserId', 'vDeviceToken')
                    ->leftJoin('devices', 'users.iUserId', '=', 'devices.iUserId')
                    ->where('users.iUserId', $user->iUserId)->get();
                if (!empty($premium)) {
                    $device = [];
                    foreach ($premium as $devices) {
                        if (!empty($devices->vDeviceToken)) {
                            $device[] = $devices->vDeviceToken;
                        }
                    }
                    $fields['data'] = [
                        'title' => 'Pora',
                        'body' => 'Subscription successfully',
                        'badge' => 'badge',
                        'sound' => 'default',
                        'icon' => asset('theme/dist/img/logo.png'),
                    ];
                    $push = pushCurlCall($device, $fields);
                }
            }
            return SuccessResponseWithResult($subscriptionData, "Subscription successfully");
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    private function GetUserData($user)
    {
        $user = Auth::user();
        $subscription = UserSubscription::select('iUserId', 'vOrderId', 'vPackageName', 'ltxSubscriptionToken', 'vProductId')->where('iUserId', $user->iUserId)->first();
        +$result = [
            "iUserId" => !empty($subscription) ? (int) $subscription->iUserId : '',
            "vProductId" => !empty($subscription) ? (string) $subscription->vProductId : '',
            "vPackageName" => !empty($subscription) ? (string) $subscription->vPackageName : '',
            "vOrderId" => !empty($subscription) ? (string) $subscription->vOrderId : '',
            "ltxSubscriptionToken" => !empty($subscription) ? $subscription->ltxSubscriptionToken : '',
        ];
        return $result;
    }
}
