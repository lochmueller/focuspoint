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
\HDNET\Autoloader\Loader::extLocalconf('HDNET', 'focuspoint', $loader);