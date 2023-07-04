<?php

declare(strict_types=1);

use HDNET\Focuspoint\Utility\TcaUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_y'] = TcaUtility::getBaseConfiguration();
$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_y']['exclude'] = 1;
$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_y']['label'] = 'LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:sys_file_metadata.focus_point_y';

$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_x'] = TcaUtility::getBaseConfiguration();
$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_x']['exclude'] = 1;
$GLOBALS['TCA']['sys_file_metadata']['columns']['focus_point_x']['label'] = 'LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:sys_file_metadata.focus_point_x';

ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', 'focus_point_y,focus_point_x');
