<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Api\v1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class OauthController extends BaseController
{
    public function signup(Request $request) {
        $rules = [
            'vFirstName' => 'required',
            'vLastName' => 'required',
            'vISDCode' => 'required',
            'vMobileNumber' => 'required',
        ];

        if($this->ApiValidator($request->all(), $rules)){
            return $this->SendResponse($this->response);
        }

        $model = new User();
        $response = $model->signup($request);
        return $this->SendResponse($response);
    }

    //Login
    public function login(Request $request) {
        $rules = [
            'vEmailId' => 'required|email',
            'vPassword' => 'required',
            'vDeviceToken' => 'required',
            'tiDeviceType' => 'required|in:1,2'
        ];
        $messages = [];
        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new User();
        $response = $model->login($request);
        return $this->SendResponse($response);
    }

    # Social signin API Route
    public function socialSignin(Request $request) {
        $rules = [
            'vSocialId' => 'required',
            'tiSocialType' => 'required|in:1,2',
            'tiDeviceType' => 'required|in:1,2',
            'vDeviceToken' => 'required'
        ];
        $messages = [];
        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new User();
        $response = $model->socialSignin($request);
        return $this->SendResponse($response);
    }

    # Send OTP API
    public function sendOTP(Request $request) {
        $rules = [
                    'vISDCode' => 'required',
                    'vMobileNumber' => 'required'
                ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new User();
        $response = $model->sendOTP($request);
        return $this->SendResponse($response);
    }

    # Verify OTP API
    public function verifyOTP(Request $request) {
        $rules = [
            'vISDCode' => 'required',
            'vMobileNumber' => 'required',
            'iOTP' => 'required'
        ];

        $messages = [];

        if($this->ApiValidator($request->all(), $rules,$messages)){
            return $this->SendResponse($this->response);
        }
        $model = new User();
        $response = $model->verifyOTP($request);
        return $this->SendResponse($response);
    }
}
