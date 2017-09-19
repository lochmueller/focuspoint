<?php

/**
 * Image Utility.
 */

namespace HDNET\Focuspoint\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Image Utility.
 */
class ImageUtility
{
    /**
     * Get the allowed file extensions for the focuspoint method.
     *
     * @return array
     */
    public static function getAllowedFileExtensions()
    {
        $configuredExtensions = GeneralUtility::trimExplode(
            ',',
            strtolower($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
            true
        );
        $ignoreExtensions = ['pdf', 'ai', 'tga'];

        return array_diff($configuredExtensions, $ignoreExtensions);
    }

    /**
     * Check if the given path or extension is valid for the focuspoint.
     *
     * @param $pathOrExtension
     *
     * @return bool
     */
    public static function isValidFileExtension($pathOrExtension)
    {
        $pathOrExtension = strtolower($pathOrExtension);
        $validExtensions = self::getAllowedFileExtensions();
        if (in_array($pathOrExtension, $validExtensions)) {
            return true;
        }

        return in_array(PathUtility::pathinfo($pathOrExtension, PATHINFO_EXTENSION), $validExtensions);
    }
}
