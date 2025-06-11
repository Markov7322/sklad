<?php

return [
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', env('APP_URL')),
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'pem_file' => env('VAPID_PEM_FILE'),
    ],

    'model' => NotificationChannels\WebPush\PushSubscription::class,
    'table_name' => env('WEBPUSH_DB_TABLE', 'push_subscriptions'),
    'database_connection' => env('WEBPUSH_DB_CONNECTION', env('DB_CONNECTION', 'mysql')),
    'client_options' => [],
    'automatic_padding' => env('WEBPUSH_AUTOMATIC_PADDING', true),
];
