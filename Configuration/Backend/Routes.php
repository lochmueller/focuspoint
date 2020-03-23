<?php

use HDNET\Focuspoint\Controller\BackendController;

return [
    'focuspoint' => [
        'path' => '/wizard/focuspoint',
        'target' => BackendController::class . '::wizardAction'
    ]
];

