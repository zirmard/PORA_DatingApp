<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubscriptionPlan extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';

    protected $table = 'subscription_plans';
    protected $primaryKey = 'iSubscriptionPlanId';
    public $timestamps = false;

    protected $fillable = [
        'dbPlanPrice',
        'vPlanType',
        'txPlanDescription',
        'vSkuId',
        'tsCreatedAt',
        'tsUpdatedAt',
    ];

    protected $dates = [
        'tsCreatedAt',
        'tsUpdatedAt',
    ];

    # Get Subscription Plan List API
    public static function getSubscriptionPlanList()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $getListOfPlans = SubscriptionPlan::select('iSubscriptionPlanId', 'dbPlanPrice', 'vPlanType', 'vSkuId')->where(['tiIsActive' => 1])->get()->toArray();
                if ($getListOfPlans) {
                    $checkPlan = Transaction::where(['iUserId' => $user->iUserId])->first();
                    foreach ($getListOfPlans as $key => $value) {
                        if (!empty($checkPlan)) {
                            if ($checkPlan->iSubscriptionPlanId == $value['iSubscriptionPlanId'] && $checkPlan->tiIsActive == 1) {
                                $tiIsSelected = 1;
                            } else {
                                $tiIsSelected = 0;
                            }
                        } else {
                            $tiIsSelected = 0;
                        }
                        $response[] = array(
                            'iSubscriptionPlanId' => (int) $value['iSubscriptionPlanId'],
                            'dbPlanPrice' => (string) $value['dbPlanPrice'],
                            'vPlanType' => $value['vPlanType'],
                            'tiIsSelected' => $tiIsSelected,
                            'vSkuId' => $value['vSkuId'],
                        );
                    }
                    return SuccessResponseWithResult($response, 'api.subscription_plan_success');
                } else {
                    return ErrorResponse('api.no_plan_found');
                }
            } else {
                return ErrorResponse('api.user_not_found');
            }
        } catch (\Exception$ex) {
            return ExceptionResponse($ex);
        }
    }

}
