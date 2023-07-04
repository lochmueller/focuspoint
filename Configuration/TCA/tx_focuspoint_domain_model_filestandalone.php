<?php

declare(strict_types=1);


// @todo add missing stuuff

use HDNET\Focuspoint\Utility\TcaUtility;

return  [
    'ctrl' => [
        'hideTable' => true,
        'rootLevel' => 1,
    ],
    'columns' => [
        'focus_point_y' => TcaUtility::getBaseConfiguration('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:tx_focuspoint_domain_model_filestandalone.focus_point_y'),
        'focus_point_x' => TcaUtility::getBaseConfiguration('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:tx_focuspoint_domain_model_filestandalone.focus_point_x'),
    ],
];
