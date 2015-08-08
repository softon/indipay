<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Indipay Service Config
    |--------------------------------------------------------------------------
    |   gateway = Log / PayUMoney / CCAvenue
    |   view    = File
    */

    'gateway' => 'CCAvenue',                // Replace with the name of appropriate gateway

    'ccavenue' => [                         // CCAvenue Parameters
        'merchant_id'  => env('INDIPAY_MERCHANT_ID', ''),
        'access_code'  => env('INDIPAY_ACCESS_CODE', ''),
        'working_key' => env('INDIPAY_WORKING_KEY', ''),
        'redirect_url' => env('INDIPAY_REDIRECT_URL', ''),
        'cancel_url' => env('INDIPAY_CANCEL URL', ''),
    ],





];
