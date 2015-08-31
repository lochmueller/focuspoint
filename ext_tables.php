<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$loader = array(
    'Xclass',
    'Hooks',
    'SmartObjects',
    'ExtensionTypoScriptSetup',
    'Plugins',
    'StaticTyposcript',
    'Xclass',
    'Xclass',
);
\HDNET\Autoloader\Loader::extTables('HDNET', 'focuspoint', $loader);

$icons = array(
    'focuspoint' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('focuspoint') . 'ext_icon.png',
);
\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons($icons, 'focuspoint');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModulePath('focuspoint', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('focuspoint').'/Modules/Wizards/Focuspoint/');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['focuspoint_test'] = 'image,image_ratio'; 
