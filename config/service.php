<?php
return [
    'slack' => [
        'webhook_url' => env('SLACK_WEBHOOK_URL'),
    ],
    'shukiin' => [
        'email' => env('SHUKIIN_EMAIL'),
        'password' => env('SHUKIIN_PASSWORD'),
        'url' => 'https://shukiin.com/'
    ],
];