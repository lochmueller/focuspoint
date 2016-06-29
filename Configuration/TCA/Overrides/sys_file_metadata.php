<?php

use HDNET\Autoloader\Utility\ArrayUtility;
use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\Focuspoint\Utility\TcaUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['sys_file_metadata'] = ModelUtility::getTcaOverrideInformation('focuspoint', 'sys_file_metadata');

$custom = [
    'columns' => [
        'focus_point_y' => TcaUtility::getBaseConfiguration(),
        'focus_point_x' => TcaUtility::getBaseConfiguration(),
    ],
];

$GLOBALS['TCA']['sys_file_metadata'] = ArrayUtility::mergeRecursiveDistinct(
    $GLOBALS['TCA']['sys_file_metadata'],
    $custom
);

ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', 'focus_point_y,focus_point_x');
