<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$loader = [
    'SmartObjects',
    'Plugins',
];
\HDNET\Autoloader\Loader::extTables('HDNET', 'focuspoint', $loader);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['focuspoint_test'] = 'image,image_ratio';
