<?php

return [
    'b2b' => [
        'initiator' => env ('B2B_INITIATOR'),
        'password' => env ('B2B_PASSWORD'),
        'source' => env ('B2B_SOURCE'),
        'result_url' => env ('B2B_RESULT_URL'),
        'callback_url' => env ('B2B_CALLBACK_URL'),
        'securitycredential' => env ('B2B_SECURITY_CREDENTIAL'),
    ],
    'helaplus_api_token'=>env('HELAPLUS_API_TOKEN')
];