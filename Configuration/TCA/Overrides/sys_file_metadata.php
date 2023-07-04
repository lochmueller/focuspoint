<?php

declare(strict_types=1);

use HDNET\Focuspoint\Utility\TcaUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_y'] = TcaUtility::getBaseConfiguration('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:sys_file_metadata.focus_point_y');
$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_x'] = TcaUtility::getBaseConfiguration('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:sys_file_metadata.focus_point_x');

ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', 'focus_point_y,focus_point_x');
