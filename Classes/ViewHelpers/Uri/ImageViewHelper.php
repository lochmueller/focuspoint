<?php

/**
 * Get the URI of the cropped image
 *
 * @package Focuspoint\Service
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\ViewHelpers\Uri;

use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;

/**
 * Get the URI of the cropped image
 */
class ImageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Uri\ImageViewHelper
{

    /**
     * Resize the image (if required) and returns its path. If the image was not changed, the path will be equal to $src
     *
     * @see http://typo3.org/documentation/document-library/references/doc_core_tsref/4.2.0/view/1/5/#id4164427
     *
     * @param string                           $src
     * @param FileInterface|AbstractFileFolder $image
     * @param string                           $width              width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string                           $height             height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param integer                          $minWidth           minimum width of the image
     * @param integer                          $minHeight          minimum height of the image
     * @param integer                          $maxWidth           maximum width of the image
     * @param integer                          $maxHeight          maximum height of the image
     * @param boolean                          $treatIdAsReference given src argument is a sys_file_reference record
     * @param string                           $ratio
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string path to the image
     */
    public function render(
        $src = null,
        $image = null,
        $width = null,
        $height = null,
        $minWidth = null,
        $minHeight = null,
        $maxWidth = null,
        $maxHeight = null,
        $treatIdAsReference = false,
        $ratio = '1:1'
    ) {
        $internalImage = $this->getImage($src, $treatIdAsReference);
        /** @var \HDNET\Focuspoint\Service\FocusCropService $service */
        $service = GeneralUtility::makeInstance('HDNET\\Focuspoint\\Service\\FocusCropService');
        $src = $service->getCroppedImageSrcByFile($internalImage, $ratio);
        return parent::render($src, null, $width, $height, $minWidth, $minHeight, $maxWidth, $maxHeight, false);
    }

    /**
     * get the image
     *
     * @param $src
     * @param $treatIdAsReference
     *
     * @return \TYPO3\CMS\Core\Resource\File|FileInterface|\TYPO3\CMS\Core\Resource\FileReference|\TYPO3\CMS\Core\Resource\Folder
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     */
    protected function getImage($src, $treatIdAsReference)
    {
        $resourceFactory = ResourceFactory::getInstance();
        if (!MathUtility::canBeInterpretedAsInteger($src)) {
            return $resourceFactory->retrieveFileOrFolderObject($src);
        }
        if (!$treatIdAsReference) {
            return $resourceFactory->getFileObject($src);
        }
        $image = $resourceFactory->getFileReferenceObject($src);
        return $image->getOriginalFile();
    }
}
