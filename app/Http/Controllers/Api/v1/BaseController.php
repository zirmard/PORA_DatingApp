<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    public $response = ['responseCode' => 200,'responseMessage' => ''];
    public function ApiValidator($fields, $rules,$message = []){
        $validator = Validator::make($fields, $rules,$message);

        if($validator->fails()){
            foreach($validator->errors()->messages() as $message){
                $this->response = ['responseCode' => 400,'responseMessage' => $message[0]];
                return true;
            }
        }
        return false;
    }
    
    public function SendResponse($response){
        return response()->json($response,  200);
    }
}