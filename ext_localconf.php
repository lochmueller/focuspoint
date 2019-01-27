<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$loader = [
    'Xclass',
    // 'Hooks', Called manually
    'SmartObjects',
    'ExtensionTypoScriptSetup',
    'Plugins',
    'StaticTyposcript'
];
\HDNET\Autoloader\Loader::extLocalconf('HDNET', 'focuspoint', $loader);

\HDNET\Autoloader\Utility\ExtendedUtility::addHook('TYPO3_CONF_VARS|SC_OPTIONS|fileList|editIconsHook', \HDNET\Focuspoint\Hooks\FileList::class);
\HDNET\Autoloader\Utility\ExtendedUtility::addHook('TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_content.php|getData', \HDNET\Focuspoint\Hooks\GetData::class);
\HDNET\Autoloader\Utility\ExtendedUtility::addHook('TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tceforms_inline.php|tceformsInlineHook', \HDNET\Focuspoint\Hooks\InlineRecord::class);

$is9orHigher = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('9.0');
if (!$is9orHigher) {
    // @deprecated (not used since TYPO3 9.x.x anymore
    \HDNET\Autoloader\Utility\ExtendedUtility::addHook('TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tceforms.php|dbFileIcons', \HDNET\Focuspoint\Hooks\GroupItem::class);
}
