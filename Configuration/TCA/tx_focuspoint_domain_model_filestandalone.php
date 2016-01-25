<?php

/**
 * Base TCA generation for the model HDNET\\Focuspoint\\Domain\\Model\\FileStandalone
 */

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\Focuspoint\Utility\TcaUtility;

$base = ModelUtility::getTcaInformation('HDNET\\Focuspoint\\Domain\\Model\\FileStandalone');

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