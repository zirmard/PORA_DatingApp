<?php

use Carbon\Carbon;
use Google\Service\AndroidPublisher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Twilio\Rest\Client;
# in app
use Google_Client as GoogleClient;
use Google_Service_AndroidPublisher as GoogleServiceAndroidPublisher;
use Google_Service_Exception as GoogleServiceException;


//Generate random unique string
function GenerateRandomString($length = 0)
{
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((float)microtime() * 1000000);
    $i = 0;
    $pass = '';
    $length = ($length > 0) ? $length : 128;
    while ($i <= $length) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function BackgroundProcess($url, $postfields)
{
    $ch = curl_init($url);

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_NOSIGNAL => 1, //to timeout immediately if the value is < 1000 ms
        CURLOPT_TIMEOUT_MS => 100, //The maximum number of mseconds to allow cURL functions to execute
        CURLOPT_VERBOSE => 1,
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_HEADER => 1,
        CURLOPT_FRESH_CONNECT => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false
    ));

    curl_exec($ch);

    curl_close($ch);
    return true;
}

function TimeElapsedString($datetime, $full = false)
{
    $now = new \DateTime;
    $ago = new \DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function GetUuid()
{
    return Uuid::uuid6();
}

function ExceptionResponse($ex)
{
    $message = $ex->getMessage();
    if (!App::environment('production')) {
        $message = $message . " (Path: " . $ex->getFile() . " : Line no " . $ex->getLine() . ")";
        Log::error($message);
    }
    return [
        'responseCode' => 400,
        'responseMessage' => $message
    ];
}

function ErrorResponse($message_key, $placeholders = [])
{

    return [
        'responseCode' => $message_key == 'api.unauthenticated' ? 401 : 400,
        'responseMessage' => __($message_key, $placeholders)
    ];
}

function ErrorResponseWithResult($result, $message_key, $placeholders = [])
{
    return [
        'responseCode' => 400,
        'responseMessage' => __($message_key, $placeholders),
        'responseData' => $result
    ];
}

function SuccessResponse($message_key, $placeholders = [])
{
    return [
        'responseCode' => 200,
        'responseMessage' => __($message_key, $placeholders)
    ];
}

function SuccessResponseWithResult($result, $message_key, $placeholders = [])
{
    return [
        'responseCode' => 200,
        'responseMessage' => __($message_key, $placeholders),
        'responseData' => $result
    ];
}

function FormSuccessResponse($request, $message_key, $placeholders = [])
{
    $request->session()->flash('success', __($message_key, $placeholders));
}

function FormErrorResponse($request, $message_key, $placeholders = [])
{
    $request->session()->flash('error', __($message_key, $placeholders));
}

function GetSocialId($tiSocialType, $vSocialId)
{
    try {
        if ($tiSocialType == 1) { //Facebook
            $result = SendCurlRequest(getenv('FB_URL') . '?access_token=' . $vSocialId . '&fields=' . getenv('FB_RETURN_FIELDS'));
            if (!isset($result->error)) {
                $params = [
                    'vSocialId' => $result->id,
                    'vName' => $result->first_name ?? NULL . '' . $result->last_name ?? NULL,
                    'vEmailId' => $result->email ?? NULL,
                    'tiSocialType' => 1,
                    // 'vImageUrl' => $result->picture->data->url,
                ];
                return SuccessResponseWithResult($params, "Social login");
            } else {
                return ErrorResponse($result->error->message);
            }
        } else if ($tiSocialType == 2) { //Google
            $result = SendCurlRequest(getenv('GOOGLE_URL') . '&access_token=' . $vSocialId);
            if (!isset($result->error)) {
                $resource = explode('/', $result->resourceName);
                $params = [
                    'vSocialId' => $resource[1],
                    'vName' => $result->names[0]->givenName ?? NULL . ' ' . $result->names[0]->familyName ?? NULL,
                    'vEmailId' => !empty($result->emailAddresses[0]->value) ? $result->emailAddresses[0]->value : "",
                    'tiSocialType' => 2,
                    'vImageUrl' => $result->photos[0]->url,
                ];
                return SuccessResponseWithResult($params, "Social login");
            } else {
                return ErrorResponse($result->error->message);
            }
        } else {
            return ErrorResponse("api.invalid_social_type");
        }
    } catch (Exception $ex) {
        return ExceptionResponse($ex);
    }
}

function SendCurlRequest($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result = json_decode($result);
}

function sendPushIOS($registrationId, $msgData)
{
    $fields['notification'] = [
        'title' => $msgData['title'],
        'body' => $msgData['msg'],
        'badge' => $msgData['badge'],
        'sound' => !empty($msgData['sound']) ? $msgData['sound'] : 'default',
        'icon' => asset('theme/dist/img/logo.png'),
    ];
    $fields['data'] = [
        'type' => $msgData['type'],
        'iUserId' => !empty($msgData['iUserId']) ? $msgData['iUserId'] : '',
    ];
    return pushCurlCall($registrationId, $fields);
}

function sendPushAndroid($registrationId, $msgData)
{
    $fields['data'] = [
        'title' => $msgData['title'],
        'body' => $msgData['msg'],
        'badge' => $msgData['badge'],
        'sound' => !empty($msgData['sound']) ? $msgData['sound'] : 'default',
        'icon' => asset('theme/dist/img/logo.png'),
        'type' => $msgData['type'],
        'iUserId' => !empty($msgData['iUserId']) ? $msgData['iUserId'] : '',
        'iAge' => $msgData['iAge'],
        'vProfileImage' => $msgData['vProfileImage'],
        'tiIsProfileImageVerified' => $msgData['tiIsProfileImageVerified'],
    ];
    return pushCurlCall($registrationId, $fields);
}

function pushCurlCall($registrationId, $fields)
{
    $url = getenv('FCM_API_SERVER_URL');
    if (is_array($registrationId)) {
        $fields['registration_ids'] = $registrationId;
    } else {
        $fields['to'] = $registrationId;
    }

    $headers = [
        'Authorization: key=' . getenv('FCM_SERVER_KEY'),
        'Content-Type: application/json',
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    return ($result) ? 1 : 0;
}

function twilioNumberIsValidCheck($number)
{
    try {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_TOKEN");

        $client = new Client($account_sid, $auth_token);

        $client->lookups->v1->phoneNumbers($number)
            ->fetch(["type" => ["carrier"]]);
        $res = ['responseCode' => 200];
        return $res;
    } catch (\Twilio\Exceptions\TwilioException $e) {
        $res = ['responseCode' => 400, 'message' => $e->getMessage()];
        return $res;
    }
}


function sendEmail($data, $param)
{
    Mail::send($param['filename'], $data, function ($message) use ($param) {
        $message->to($param['to'])->subject($param['subject']);
        $message->from($param['from']);
    });
}
















/**
 *  check android subscription
 * @return array
 */

function AndroidVerifySubscription($subscriptionData)
{
    // print_r($subscriptionData); die;
    $client = new GoogleClient();

    $client->setApplicationName(config('pora.application_name'));
    // get google in app credential
    // if ($isSendbox) {
    $KEY_FILE_LOCATION = config('pora.android_credentials_path');
    // } else {
    //     $KEY_FILE_LOCATION = config('hct.android_credentials_path_live');
    // }
    // print_r($KEY_FILE_LOCATION);
    Log::info(["android config data =>", config('pora.application_name'), config('pora.android_scopes'),$subscriptionData['vPackageName'],
    $subscriptionData['vProductId'],
    $subscriptionData['ltxSubscriptionToken']]);
    // dump($KEY_FILE_LOCATION);
    $client->setAuthConfig($KEY_FILE_LOCATION);
    // set scope
    $client->setScopes(config('pora.android_scopes'));

    // print_r($client);die;
    try {
        $service = new GoogleServiceAndroidPublisher($client);
        // print_r($service);die;
        $subscription  = $service->purchases_subscriptions->get(
            $subscriptionData['vPackageName'],
            $subscriptionData['vProductId'],
            $subscriptionData['ltxSubscriptionToken']
        );
        // print_r($subscription); die;
        Log::info(["android data =>", json_encode($subscriptionData)]);
        Log::info(["android response =>", json_encode($subscription)]);
        if (is_null($subscription)) {
            $response = [
                'status' => false,
                'error' =>  "Get blank response",
            ];
        } else {
            // echo "<pre>";
            // print_r($subscription); die;
            // convert expiration time milliseconds since Epoch to seconds since Epoch
            $expiryTimeMillis = $subscription['expiryTimeMillis'] / 1000;
            // format seconds as a datetime string and create a new UTC Carbon time object from the string
            $datetime = new Carbon($expiryTimeMillis);
            // check if the expiration date is in the past`
            if (Carbon::now()->gt($datetime)) {
                $response = [
                    'status' => false,
                    'error' =>  "subscription Expire",
                ];
            } else {
                $response = [
                    'status' => true,
                    'data' => $subscription,
                ];
            }
        }
        return $response;
    } catch (GoogleServiceException $e) {
        // exception handele
        Log::info(["android google service exception =>", $e->getMessage()]);
        return [
            'status' => false,
            'error' =>  "invalid subscription data",
            // 'error' =>  $e->getMessage(),
        ];
    }
    catch (\Throwable $e) {
        Log::info(["android exception =>", $e->getMessage()]);
        return [
            'status' => false,
            'error' =>  "invalid subscription data",
        ];
    }
}

/**
 *  cancel android subscription
 * @return array
 */

function AndroidCancelSubscription($receipt, $isSendbox)
{
    $client = new GoogleClient();

    $client->setApplicationName(config('hct.application_name'));
    // get google in app credential
    if ($isSendbox) {
        $KEY_FILE_LOCATION = config('hct.android_credentials_path');
    } else {
        $KEY_FILE_LOCATION = config('hct.android_credentials_path_live');
    }
    $client->setAuthConfig($KEY_FILE_LOCATION);
    // set scope
    $client->setScopes(config('hct.android_scopes'));
    $service = new GoogleServiceAndroidPublisher($client);
    // echo "<pre>";
    // print_r($service); die;
    try {
        $subscription  = $service->purchases_subscriptions->cancel(
            $receipt['packageName'],
            $receipt['productId'],
            $receipt['purchaseToken']
        );

        if (is_null($subscription)) {
            return [
                'status' => false,
                'error' =>  "Get blank response",
            ];
        } else {
            return [
                'status' => true,
                'data' => $subscription,
            ];
        }
    } catch (GoogleServiceException $e) {
        // exception handele
        return [
            'status' => false,
            'error' =>  "invalide subscription data",
        ];
    }
}
function customSelect($col,$forId,$labelname,$selectname, $data,$selectedValue,$msg='') {
    $html = "<div class='col-md-$col'>
                <div class='form-group'>
                    <label for='$forId'>$labelname :</label>
                        <select class='form-control' id='$forId' name='$selectname'> ";

            foreach($data as $key => $value) {
                $html .= "<option value='";
                $html .= ($selectname == 'vEarnings' || $selectname == 'vZodiacSignName' || $selectname == 'vEducationQualification' || $selectname == 'vPreferredEarnings' || $selectname == 'vEthnicGroup') ? $value : $key;
                $html .= "'";
                $html .= ($selectname == 'vEarnings' || $selectname == 'vZodiacSignName' || $selectname == 'vEducationQualification' || $selectname == 'vPreferredEarnings' || $selectname == 'vEthnicGroup') ? (($value==$selectedValue) ? 'selected': '' ) : (($key==$selectedValue) ? 'selected': '' );
                $html .= ">$value</option>";
             }
    $html .= '</select></div></div>';
    echo $html;
}
function customType($col,$forId,$labelname,$type,$typename,$value,$placehoder,$msg='') {
    $html = "<div class='col-md-$col'>
                <div class='form-group'>
                    <label for='$forId'>$labelname :</label>
                    <input type='$type' name='$typename' value='$value' class='form-control' id='$forId' placeholder='$placehoder'>";
                    if(!empty($msg)) {
                        $html .= "<span class='text-danger'>$msg</span>";
                    }
    $html .="</div></div>";
    echo $html;
}

function customTableRow($labelname,$data=[],$selectedValue) {
    $html = "<tr>";
    $html .= "<td style='width:70%'><label>$labelname</label></td>";
    $html .= "<td style='width:30%'>";
    if(is_array($data) && !empty($data)) {
        foreach($data as $key => $value) {
            if($selectedValue == $key)
                $html .= (empty($value)) ? 'N/A' : $value ;
        }
    } else {
        $html .= "$selectedValue";
    }
    $html.="</td></tr>";
    echo $html;
}

