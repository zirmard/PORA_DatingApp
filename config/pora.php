<?php

return [
    //Android In App Purchase Config Data
    'android_credentials_path' => env('ANDROID_CREDENTIALS_PATH', storage_path('pora1-9b888-5dede34696bc.json')), // Service account json file
    'android_credentials_path_live' => env('ANDROID_CREDENTIALS_PATH', storage_path('pora1-9b888-5dede34696bc.json')), // Service account json file
    'application_name' => env('APPLICATION_NAME', "Pora-Live"), // application name
    'android_scopes' => [
        'https://www.googleapis.com/auth/androidpublisher'
    ],
];
