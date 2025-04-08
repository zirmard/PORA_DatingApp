<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Backend\Faq;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends BaseController
{
    # Get Content Page by Slug API
    public function contentPages($vSlug) {
        try {
            $result = DB::table("content_pages")
                        ->selectRaw("vPageName,txContent,IFNULL(iUpdatedAt,'') as iUpdatedAt")
                        ->where(["vSlug" => $vSlug,"tiIsActive" => 1])->first();

            if(!empty($result)) {
                return SuccessResponseWithResult($result,"api.content_page");
            } else {
                return ErrorResponse("api.invalid_content_page");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }

    # List Of FAQs API
    public function faqs() {
        try {
            $result = Faq::where(['tiIsActive' => 1])->select('vQuestionCategory','vQuestion', 'txAnswer')->orderBy('iFaqId', 'DESC')->get()->toArray();
            if(!empty($result)) {
                return SuccessResponseWithResult($result,"api.faqs_list");
            } else {
                return ErrorResponse("api.no_faqs");
            }
        } catch (Exception $ex) {
            return ExceptionResponse($ex);
        }
    }
}
