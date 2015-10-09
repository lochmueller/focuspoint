<?php
/**
 * Get data for focuspoint
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Hooks;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectGetDataHookInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Get data for focuspoint
 *
 * @hook TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_content.php|getData
 */
class GetData implements ContentObjectGetDataHookInterface
{

    /**
     * Extends the getData()-Method of ContentObjectRenderer to process more/other commands
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
        $parts = explode(':', $getDataString);
        if (isset($parts[0]) && isset($parts[1]) && $parts[0] === 'fp') {
            $fileObject = $parentObject->getCurrentFile();
            if (!($fileObject instanceof FileReference)) {
                return $returnValue;
            }
            $originalFile = $fileObject->getOriginalFile();
            switch ($parts[1]) {
                case 'x':
                case 'y':
                    $metaData = $originalFile->_getMetaData();
                    return ($metaData['focus_point_' . $parts[1]] / 100);
                case 'w':
                case 'h':
                    $fileName = GeneralUtility::getFileAbsFileName($fileObject->getPublicUrl(true));
                    if (file_exists($fileName)) {
                        $sizes = getimagesize($fileName);
                        return $sizes[($parts[1] == 'w' ? 0 : 1)];
                    }
                    break;
            }
        }
        return $returnValue;
    }
}
