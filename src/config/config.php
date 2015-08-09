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

    'testMode'  => false,                   // True for Testing the Gateway

    'ccavenue' => [                         // CCAvenue Parameters
        'merchantId'  => env('INDIPAY_MERCHANT_ID', ''),
        'accessCode'  => env('INDIPAY_ACCESS_CODE', ''),
        'workingKey' => env('INDIPAY_WORKING_KEY', ''),
        'redirectUrl' => env('INDIPAY_REDIRECT_URL', ''),
        'cancelUrl' => env('INDIPAY_CANCEL_URL', ''),
        'currency' => env('INDIPAY_CURRENCY', 'INR'),
        'language' => env('INDIPAY_LANGUAGE', 'EN'),
    ],





];
