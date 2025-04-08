<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Azure Communication Services
    |--------------------------------------------------------------------------
    |
    | This option controls the default Azure Communication Services connection
    | that gets used while sending messages to users.
    |
    */

    'smsEndPoint' => env('AZURE_COMMUNICATION_SERVICES_SMS_ENDPOINT', 'http://localhost:808php art 0'),
    'smsKey' => env('AZURE_COMMUNICATION_SERVICES_SMS_KEY', 'YOUR_SMS_KEY'),
    'smsPhoneNumber' => env('AZURE_COMMUNICATION_SERVICES_SMS_NUMBER', '+13056991653'),
    'testSmsPhoneNumber' => env('AZURE_COMMUNICATION_SERVICES_TEST_SMS_NUMBER', '+13056991653'),
    'servicePrincipalId'    => env('AZURE_COMMUNICATION_SERVICES_SERVICE_PRINCIPAL_ID'),
    'servicePrincipalSecret'    => env('AZURE_COMMUNICATION_SERVICES_SERVICE_PRINCIPAL_SECRET'),
    'emailEndPoint' => env('AZURE_COMMUNICATION_SERVICES_EMAIL_ENDPOINT', NULL),
    'emailMailFrom' => env('AZURE_COMMUNICATION_SERVICES_EMAIL_FROM', 'DoNotReply@milandm.com'),
    'testEmailRecipientAddress' => env('AZURE_COMMUNICATION_SERVICES_TEST_EMAIL_RECIPIENT', NULL),
    'testEmailHostPort' => env('AZURE_COMMUNICATION_SERVICES_TEST_HOST_PORT', 'http://localhost:8000'),
];
