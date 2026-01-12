<?php

return [
    'publicPath' => public_path('media'),

    'storagePath' => storage_path('app/public/media'),

    'serverPath' => base_path('server_storage/media'),

    'useStorage' => env('MEDIA_USE_STORAGE', false),

    'useServerStorage' => env('MEDIA_USE_SERVER_STORAGE', true),

    'publicUrl' => env('APP_URL').'/media',
    'storageUrl' => env('APP_URL').'/storage/app/public/media',
    'serverUrl' => env('APP_URL').'/media',

    'default_image_format' => 'webp',

];
