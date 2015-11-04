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
    'StaticTyposcript',
];
\HDNET\Autoloader\Loader::extLocalconf('HDNET', 'focuspoint', $loader);