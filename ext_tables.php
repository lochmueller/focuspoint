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

/** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'tcarecords-tx_focuspoint_domain_model_filestandalone-default',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    [
        'source' => 'EXT:focuspoint/ext_icon.svg'
    ]
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['focuspoint_test'] = 'image,image_ratio';
