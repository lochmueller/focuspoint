<?php


if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$loader = [
    'Xclass',
    'Hooks',
    'SmartObjects',
    'ExtensionTypoScriptSetup',
    'Plugins',
    'StaticTyposcript'
];
\HDNET\Autoloader\Loader::extTables('HDNET', 'focuspoint', $loader);

$icons = [
    'focuspoint' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('focuspoint') . 'ext_icon.png',
];

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['focuspoint_test'] = 'image,image_ratio';
