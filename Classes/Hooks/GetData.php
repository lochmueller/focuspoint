<?php

/**
 * Get data for focuspoint.
 */

namespace HDNET\Focuspoint\Hooks;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectGetDataHookInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Get data for focuspoint.
 *
 * @hook TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_content.php|getData
 */
class GetData implements ContentObjectGetDataHookInterface
{
    /**
     * Extends the getData()-Method of ContentObjectRenderer to process more/other commands.
     *
     * @param string                $getDataString Full content of getData-request e.g. "TSFE:id // field:title // field:uid
     * @param array                 $fields        Current field-array
     * @param string                $sectionValue  Currently examined section value of the getData request e.g. "field:title
     * @param string                $returnValue   Current returnValue that was processed so far by getData
     * @param ContentObjectRenderer $parentObject  Parent content object
     *
     * @return string Get data result
     */
    public function getDataExtension(
        $getDataString,
        array $fields,
        $sectionValue,
        $returnValue,
        ContentObjectRenderer &$parentObject
    ) {
        $parts = \explode(':', $getDataString);
        if (isset($parts[0], $parts[1]) && 'fp' === $parts[0]) {
            $fileObject = $parentObject->getCurrentFile();
            if (!($fileObject instanceof FileReference)) {
                return $returnValue;
            }
            $originalFile = $fileObject->getOriginalFile();
            switch ($parts[1]) {
                case 'x':
                case 'y':
                    $metaData = $originalFile->_getMetaData();

                    return $metaData['focus_point_' . $parts[1]] / 100;
                case 'xp':
                case 'yp':
                    $metaData = $originalFile->_getMetaData();

                    return (float) $metaData['focus_point_' . \mb_substr($parts[1], 0, 1)];
                case 'xp_positive':
                case 'yp_positive':
                    $metaData = $originalFile->_getMetaData();
                    if ('xp_positive' === $parts[1]) {
                        return (int) (\abs($metaData['focus_point_' . \mb_substr($parts[1], 0, 1)] + 100) / 2);
                    }

                    return (int) (\abs($metaData['focus_point_' . \mb_substr($parts[1], 0, 1)] - 100) / 2);
                case 'w':
                case 'h':
                    $fileName = GeneralUtility::getFileAbsFileName($fileObject->getPublicUrl(true));
                    if (\file_exists($fileName)) {
                        $sizes = \getimagesize($fileName);

                        return $sizes[('w' === $parts[1] ? 0 : 1)];
                    }
                    break;
            }
        }

        return $returnValue;
    }
}
