<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Api\v1\UserPreferences;
use Illuminate\Http\Request;

class UserPreferenceController extends BaseController
{
    public function getDiscover(Request $request) {
        $model = new UserPreferences();
        $response = $model->getDiscover($request);
        return $this->SendResponse($response);
    }
}
