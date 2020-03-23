<?php

use HDNET\Focuspoint\Controller\Wizard\FocuspointController;

return [
    'focuspoint' => [
        'path' => '/wizard/focuspoint',
        'target' => FocuspointController::class . '::mainAction'
    ]
];

