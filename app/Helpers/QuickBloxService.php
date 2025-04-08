<?php

namespace App\Helpers;

use Exception;

class QuickBloxService
{

    public static $qb_login, $qb_password;
    public  $QbLoginId;
    public static function QuickBloxConfig($key = '')
    {
        $return = [
            'QB_APPLICATION_ID' => config('services.quickblox.app_id'),
            'QB_AUTH_KEY' => config('services.quickblox.auth_key'),
            'QB_AUTH_SECRET' => config('services.quickblox.auth_secret'),
            'QB_SUPER_ADMIN_UNAME' => config('services.quickblox.login'),
            'QB_SUPER_ADMIN_PASSWORD' => config('services.quickblox.password'),
            'QB_DEFAULT_USER_PASSWORD' => config('services.quickblox.default_user_password'),
            'QB_URL_API' => config('services.quickblox.app_url'),
            'QB_URL_CHAT' => config('services.quickblox.chat_url'),
        ];
        return $key ? $return[$key] : $return;
    }

    public static function qbConfigAppID()
    {
        return self::QuickBloxConfig('QB_APPLICATION_ID');
    }

    public static function qbConfigAppKey()
    {
        return self::QuickBloxConfig('QB_AUTH_KEY');
    }

    public static function qbConfigAppSecret()
    {
        return self::QuickBloxConfig('QB_AUTH_SECRET');
    }

    public static function qbConfigAdminUsername()
    {
        return self::QuickBloxConfig('QB_SUPER_ADMIN_UNAME');
    }

    public static function qbConfigAdminPassword()
    {
        return self::QuickBloxConfig('QB_SUPER_ADMIN_PASSWORD');
    }

    public static function qbConfigDefaultPassword()
    {
        return self::QuickBloxConfig('QB_DEFAULT_USER_PASSWORD');
    }

    public static function qbConfigApiUrl($plain = false)
    {
        return str_replace(($plain ? ['https://', 'http://'] : []), '', self::QuickBloxConfig('QB_URL_API'));
    }

    public static function qbConfigChatUrl($plain = false)
    {
        return str_replace(($plain ? ['https://', 'http://'] : []), '', self::QuickBloxConfig('QB_URL_CHAT'));
    }

    public static function QuickBloxSession($is_super_admin = FALSE)
    {
        $loginOrEmail =  $is_super_admin ? self::qbConfigAdminUsername() : self::$qb_login;
        $password = $is_super_admin ? self::qbConfigAdminPassword() : self::$qb_password;
        // Generate signature
        $nonce = rand();
        $timestamp = time();

        $post_arr = [
            'application_id' => self::qbConfigAppID(),
            'auth_key' => self::qbConfigAppKey(),
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'user[login]' => $loginOrEmail,
            'user[password]' => $password,
        ];

        $signature_string = "application_id=" . self::qbConfigAppID() .
            "&auth_key=" . self::qbConfigAppKey() .
            "&nonce=" . $nonce .
            "&timestamp=" . $timestamp .
            "&user[login]=" . $loginOrEmail .
            "&user[password]=" . $password;

        $signature = hash_hmac('sha1', $signature_string, self::qbConfigAppSecret());

        $post_arr['signature'] = $signature;

        // Build post body
        $post_body = http_build_query($post_arr);
        // Configure cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::qbConfigApiUrl() . '/session.json');
        curl_setopt($curl, CURLOPT_POST, true); // Use POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POSTREDIR, true);

        // Execute request and read response
        $response = curl_exec($curl);
        // Check errors
        if ($response) {
            // Close connection
            curl_close($curl);
            return $response;
        } else {
            $error = curl_error($curl) . '(' . curl_errno($curl) . ')';
            // Close connection
            curl_close($curl);
            return $error;
        }
    }


    public static function checkQbUserExits($login)
    {

        try {
            $QBsession = self::QuickBloxSession(true);
            $QBsession = json_decode($QBsession, true);
            if (isset($QBsession['session'])) {
                $token = $QBsession['session']['token'];
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, self::qbConfigApiUrl() . '/users/by_email.json?email=' . urlencode($login));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

                $headers = array();
                $headers[] = 'Quickblox-Rest-Api-Version: 0.1.0';
                $headers[] = 'Qb-Token:' . $token;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $response = json_decode($result, true);
                if(isset($response['user']) && $response['user']) {
                    return ['code'=> 200, 'message'=>'user detail','data'=>$response['user']];
                } else {
                    return ['code'=> 404, 'message'=>$response['message']];
                }
            } else {
                return $QBsession;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function QuickBloxUserCreate($new_user_info)
    {
        $QBsession = self::QuickBloxSession(true);
        $QBsession = json_decode($QBsession, true);
        try {
            if (isset($QBsession['session'])) {
                $token = $QBsession['session']['token'];
                $post_body = http_build_query($new_user_info);
                $url = self::qbConfigApiUrl() . "/users.json";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true); // Use POST
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('QB-Token: ' . $token));
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($curl);
                curl_close($curl);
                return !empty($response) ? json_decode($response, true) : '';
            } else {
                return $QBsession;
            }
        }
        //catch exception
         catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public static function createDialog($occupants_ids, $group_name = '')
    {
        $QBsession  = self::QuickBloxSession(FALSE);
        $QBsession  = json_decode($QBsession, true);
        $errors = [];
        if (isset($QBsession['session'])) {
            $token = $QBsession['session']['token'];
            try {
                $ch = curl_init();
                if(empty($group_name)) {
                    $params = [
                        'type' => 3,  // Private dialog
                        'occupants_ids' => $occupants_ids,
                    ];
                } else {
                    $params = [
                        'type' => 2, // Group dialog
                        'name' => $group_name,
                        'occupants_ids' => $occupants_ids,
                    ];
                }

                curl_setopt($ch, CURLOPT_URL, self::qbConfigApiUrl() . "/chat/Dialog.json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/json",
                    "Qb-Token: " . $token
                ]);

                $result = curl_exec($ch);

                if (curl_errno($ch)) {
                    $errors[] = 'Error: ' . curl_error($ch);
                }
                curl_close($ch);

                if (!$errors) {
                    $result = json_decode($result, true);
                    if (isset($result['_id'])) {
                        return $result;
                    } else {
                        foreach ($result as $key => $row) {
                            foreach ($row as $v) $errors[] = $key . ' ' . $v;
                        }
                    }
                }
            } catch (Exception $e) {
                $errors[] = 'Error: ' . $e->getMessage();
            }
        } else {
            $errors[] = 'Quickblox access token required.';
        }
        return ['errors' => $errors];
    }

    public function QuickBloxSessionObj($is_super_admin = false)
    {
        $loginOrEmail = $is_super_admin ? self::qbConfigAdminUsername() : $this->QbLoginId;
        $password = $is_super_admin ? self::qbConfigAdminPassword() : self::qbConfigDefaultPassword();
        // Generate signature
        $nonce = random_int(0,99);
        $timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone

        $str = "&user[login]=" . $loginOrEmail;
        $post_arr = array(
            'application_id' => self::qbConfigAppID(),
            'auth_key' => self::qbConfigAppKey(),
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'user[login]' => $loginOrEmail,
            'user[password]' => $password,
        );

        $signature_string = "application_id=" . self::qbConfigAppID() .
        "&auth_key=" . self::qbConfigAppKey() .
            "&nonce=" . $nonce .
            "&timestamp=" . $timestamp . $str .
            "&user[password]=" . $password;

        $signature = hash_hmac('sha1', $signature_string, self::qbConfigAppSecret());

        $post_arr['signature'] = $signature;

        // Build post body
        $post_body = http_build_query($post_arr);
        // Configure cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::qbConfigApiUrl() . '/session.json');
        curl_setopt($curl, CURLOPT_POST, true); // Use POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POSTREDIR, true);

        // Execute request and read response
        $response = curl_exec($curl);
        // Check errors
        if ($response) {
            // Close connection
            curl_close($curl);
            return $response;
        } else {
            $error = curl_error($curl) . '(' . curl_errno($curl) . ')';
            // Close connection
            curl_close($curl);
            return $error;
        }
    }

    public function QuickBloxUpdateUserById($id,$vQbLogin, $data)
    {
        $this->QbLoginId = $vQbLogin;
        $QBsession = json_decode($this->QuickBloxSessionObj(), true);
        try {
            if (isset($QBsession['session']['token'])) {
                $token = $QBsession['session']['token'];
                $url = self::qbConfigApiUrl() . "/users/" . $id . ".json";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); // Setup post body
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('QB-Token: ' . $token));
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($curl);
                curl_close($curl);
                return $response ? json_decode($response, true) : [];
            }
        } catch (Exception $e) {
            return [];
        }
        return [];
    }
}
