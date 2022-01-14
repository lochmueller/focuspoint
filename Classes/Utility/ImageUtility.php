<?php

declare(strict_types=1);

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
     */
    public static function getAllowedFileExtensions(): array
    {
        $configuredExtensions = GeneralUtility::trimExplode(
            ',',
            mb_strtolower($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
            true
        );
        $ignoreExtensions = ['pdf', 'ai', 'tga'];

        return \array_diff($configuredExtensions, $ignoreExtensions);
    }

    /**
     * Check if the given path or extension is valid for the focuspoint.
     *
     * @param $pathOrExtension
     */
    public static function isValidFileExtension(string $pathOrExtension): bool
    {
        $pathOrExtension = mb_strtolower($pathOrExtension);
        $validExtensions = self::getAllowedFileExtensions();
        if (\in_array($pathOrExtension, $validExtensions, true)) {
            return true;
        }

        return \in_array(PathUtility::pathinfo($pathOrExtension, PATHINFO_EXTENSION), $validExtensions, true);
    }
}
