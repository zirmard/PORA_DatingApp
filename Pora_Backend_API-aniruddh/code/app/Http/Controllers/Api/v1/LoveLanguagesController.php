<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\AWSHelper;
use App\Models\Backend\LoveLanguages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoveLanguagesController extends BaseController
{
    public function loveLanguages() { // Love Languages List API
        try {
            $result = LoveLanguages::where(['tiIsActive' =>1])->select('iLoveLanguageId','vLoveLanguage', 'vLoveLanguageLogo')->get()->toArray();

            foreach($result as $key => $value) {
                $result[$key]['vLoveLanguageLogo'] = AWSHelper::getCloundFrontUrl($value['vLoveLanguageLogo'], AWS_LOVE_LANGUAGE_ICONS);
            }

            if(!empty($result)) {
                return SuccessResponseWithResult($result,"api.love_languages_success");
            } else {
                return ErrorResponse("api.love_languages_not_found");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }
}
