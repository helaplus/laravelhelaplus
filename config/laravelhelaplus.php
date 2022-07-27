<?php

return [
    //b2b credentials
    'b2b' => [
        'initiator' => env ('B2B_INITIATOR'),
        'password' => env ('B2B_PASSWORD'),
        'source' => env ('B2B_SOURCE'),
        'result_url' => env ('B2B_RESULT_URL'),
        'callback_url' => env ('B2B_CALLBACK_URL'),
        'securitycredential' => env ('B2B_SECURITY_CREDENTIAL'),
        'helaplus_b2b_endpoint' => env ('HELAPLUS_B2B_ENDPOINT'),  
    ],

    //c2b credentials
    'c2b' => [
        'initiator' => env ('C2B_INITIATOR'),
        'password' => env ('C2B_PASSWORD'),
        'source' => env ('C2B_SOURCE'),
        'result_url' => env ('C2B_RESULT_URL'),
        'callback_url' => env ('C2B_CALLBACK_URL'),
        'securitycredential' => env ('C2B_SECURITY_CREDENTIAL'),
        'helaplus_c2b_endpoint' => env ('HELAPLUS_C2B_ENDPOINT'),
    ],

    //b2c credentials
    'b2c' => [
        'initiator' => env ('B2C_INITIATOR'),
        'password' => env ('B2C_PASSWORD'),
        'source' => env ('B2C_SOURCE'),
        'result_url' => env ('B2C_RESULT_URL'),
        'callback_url' => env ('B2C_CALLBACK_URL'),
        'securitycredential' => env ('B2C_SECURITY_CREDENTIAL'),
        'helaplus_b2c_endpoint' => env ('HELAPLUS_B2C_ENDPOINT'), 
    ],
    'helaplus_api_token'=>env('HELAPLUS_API_TOKEN')
];