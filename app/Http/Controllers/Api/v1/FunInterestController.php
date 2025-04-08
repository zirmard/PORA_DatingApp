<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\AWSHelper;
use App\Models\Backend\FunInterests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FunInterestController extends BaseController
{
    public function funInterests() { // Fun Interests List API
        try {
            $result = FunInterests::where(['tiIsActive' =>1])->select('iInterestId','vInterestLogo', 'vInterestName')->get()->toArray();

            foreach($result as $key => $value) {
                $result[$key]['vInterestLogo'] = AWSHelper::getCloundFrontUrl($value['vInterestLogo'], AWS_FUN_INTEREST_ICONS);
            }

            if(!empty($result)) {
                return SuccessResponseWithResult($result,"api.fun_interests_success");
            } else {
                return ErrorResponse("api.fun_interests_not_found");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }
}
