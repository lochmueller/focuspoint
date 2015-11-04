<?php

/**
 * Base TCA generation for the table sys_file_reference
 */

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\Focuspoint\Utility\TcaUtility;

$GLOBALS['TCA']['sys_file_reference'] = ModelUtility::getTcaOverrideInformation('focuspoint', 'sys_file_reference');

$custom = [
    'columns' => [
        'focus_point_y' => TcaUtility::getBaseConfiguration(),
        'focus_point_x' => TcaUtility::getBaseConfiguration(),
    ],
];

$GLOBALS['TCA']['sys_file_reference'] = ArrayUtility::mergeRecursiveDistinct($GLOBALS['TCA']['sys_file_reference'],
    $custom);