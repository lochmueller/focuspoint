<?php

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\Focuspoint\Domain\Model\FileStandalone;
use HDNET\Focuspoint\Utility\TcaUtility;

$base = ModelUtility::getTcaInformation(FileStandalone::class);

$custom = [
    'ctrl' => [
        'hideTable' => true,
        'rootLevel' => 1,
    ],
    'columns' => [
        'focus_point_y' => TcaUtility::getBaseConfiguration(),
        'focus_point_x' => TcaUtility::getBaseConfiguration(),
    ],
];

return ArrayUtility::mergeRecursiveDistinct($base, $custom);
