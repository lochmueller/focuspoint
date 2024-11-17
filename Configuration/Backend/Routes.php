<?php

declare(strict_types=1);

use HDNET\Focuspoint\Controller\BackendController;

return [
    'focuspoint' => [
        'path' => '/wizard/focuspoint',
        'target' => BackendController::class . '::wizardAction',
    ],
];
