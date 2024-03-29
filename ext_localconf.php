<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Resource\Processing\LocalCropScaleMaskHelper::class] = [
    'className' => \HDNET\Focuspoint\Xclass\LocalCropScaleMaskHelper::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['getData']['focuspoint'] = \HDNET\Focuspoint\Hooks\GetData::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['fp'] = ['HDNET\Focuspoint\ViewHelpers'];

ExtensionUtility::configurePlugin(
    'Focuspoint',
    'Test',
    [\HDNET\Focuspoint\Controller\TestController::class => 'test']
);
