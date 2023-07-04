<?php

declare(strict_types=1);

use HDNET\Focuspoint\Utility\TcaUtility;

$GLOBALS['TCA']['sys_file_reference']['columns']['focus_point_y'] = TcaUtility::getBaseConfiguration('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:sys_file_reference.focus_point_y');
$GLOBALS['TCA']['sys_file_reference']['columns']['focus_point_x'] = TcaUtility::getBaseConfiguration('LLL:EXT:focuspoint/Resources/Private/Language/locallang.xlf:sys_file_reference.focus_point_x');
