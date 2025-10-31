<?php

return [

    'weather' => [
        'key' => env('WEATHER_API'),
        'URL' => 'https://api.openweathermap.org/data/2.5'
    ],
    'currency' => [
        'key' => env('CURRENCY_API'),
        'URL' => 'https://v6.exchangerate-api.com/v6/'
    ],
    'news' => [
        'key' => env('NEWS_API'),
        'URL' => 'https://gnews.io/api/v4/'
    ],
    'holiday' => [
        'key' => env('HOLIDAY_API'),
        'URL' => 'https://calendarific.com/api/v2'
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
