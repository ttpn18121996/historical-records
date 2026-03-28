<?php

use Workbench\App\Models\User;

return [
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],
    ],
];
