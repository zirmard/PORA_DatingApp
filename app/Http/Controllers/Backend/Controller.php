<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ValidateForm($request,$validtion_rules) {
        $rules = (isset($validtion_rules["rules"]) && !empty($validtion_rules["rules"])) ? $validtion_rules["rules"] : [];
        $message = (isset($validtion_rules["messages"]) && !empty($validtion_rules["messages"])) ? $validtion_rules["messages"] : [];
        $this->validate($request, $rules, $message);
    }
}
