<?php
return [
    'adminEmail' => 'admin@example.com',

    'maxFileSize' => 1024 * 1024 * 2,
    'minFileSize' => 1024 * 50,
    'storagePath' => '@frontend/web/uploads/',
    'storageUri' => '/uploads/',

    'profilePicture' => [
        'maxWidth' => 230,
        'maxHeight' => 230,
    ],

    'postPicture' => [
        'maxWidth' => 1280,
        'maxHeight' => 860,
    ],

    'feedPostLimit' => 100,
];
