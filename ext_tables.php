<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\HDNET\Autoloader\Loader::extTables('HDNET', 'focuspoint');

$icons = array(
    'focuspoint' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('focuspoint') . 'ext_icon.png',
);
// Gives the $icon array to the sprite manager
\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons($icons, 'focuspoint');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModulePath('focuspoint',
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Modules/Wizards/Focuspoint/');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['focuspoint_test'] = 'image,image_ratio';