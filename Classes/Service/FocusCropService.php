<?php
/**
 * Crop images via focus crop
 *
 * @package Focuspoint\Service
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Service;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * Crop images via focus crop
 *
 * @author Tim Lochmüller
 */
class FocusCropService extends AbstractService
{

    /**
     * get the image
     *
     * @param $src
     * @param $image
     * @param $treatIdAsReference
     *
     * @return \TYPO3\CMS\Core\Resource\File|FileInterface|CoreFileReference|\TYPO3\CMS\Core\Resource\Folder
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     */
    public function getViewHelperImage($src, $image, $treatIdAsReference)
    {
        $resourceFactory = ResourceFactory::getInstance();
        if ($image instanceof FileReference) {
            $image = $image->getOriginalResource();
        }
        if ($image instanceof CoreFileReference) {
            return $image->getOriginalFile();
        }
        if (!MathUtility::canBeInterpretedAsInteger($src)) {
            return $resourceFactory->retrieveFileOrFolderObject($src);
        }
        if (!$treatIdAsReference) {
            return $resourceFactory->getFileObject($src);
        }
        $image = $resourceFactory->getFileReferenceObject($src);
        return $image->getOriginalFile();
    }

    /**
     * Helper function for view helpers
     *
     * @param $src
     * @param $image
     * @param $treatIdAsReference
     * @param $ratio
     *
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     *
     * @return string
     */
    public function getCroppedImageSrcForViewHelper($src, $image, $treatIdAsReference, $ratio)
    {
        $file = $this->getViewHelperImage($src, $image, $treatIdAsReference);
        return $this->getCroppedImageSrcByFile($file, $ratio);
    }

    /**
     * Get the cropped image
     *
     * @param string $fileReference
     * @param string $ratio
     *
     * @return string The new filename
     */
    public function getCroppedImageSrcByFileReference($fileReference, $ratio)
    {
        if ($fileReference instanceof FileReference) {
            $fileReference = $fileReference->getOriginalResource();
        }
        if ($fileReference instanceof CoreFileReference) {
            return $this->getCroppedImageSrcByFile($fileReference->getOriginalFile(), $ratio);
        }
        throw new \InvalidArgumentException('The given argument is not a valid file reference', 123671283);
    }

    /**
     * Get the cropped image by File Object
     *
     * @param FileInterface $file
     * @param string $ratio
     *
     * @return string The new filename
     */
    public function getCroppedImageSrcByFile(FileInterface $file, $ratio)
    {
        $absoluteImageName = GeneralUtility::getFileAbsFileName($file->getPublicUrl());
        $focusPointX = MathUtility::forceIntegerInRange((int)$file->getProperty('focus_point_x'), -100, 100, 0);
        $focusPointY = MathUtility::forceIntegerInRange((int)$file->getProperty('focus_point_y'), -100, 100, 0);

        $tempImageFolder = 'typo3temp/focuscrop/';
        $tempImageName = $tempImageFolder . $file->getSha1() . '-' . str_replace(':', '-',
                $ratio) . '-' . $focusPointX . '-' . $focusPointY . '.' . $file->getExtension();
        $absoluteTempImageName = GeneralUtility::getFileAbsFileName($tempImageName);

        if (is_file($absoluteTempImageName)) {
            return $tempImageName;
        }

        $absoluteTempImageFolder = GeneralUtility::getFileAbsFileName($tempImageFolder);
        if (!is_dir($absoluteTempImageFolder)) {
            GeneralUtility::mkdir_deep($absoluteTempImageFolder);
        }

        $imageSizeInformation = getimagesize($absoluteImageName);
        $width = $imageSizeInformation[0];
        $height = $imageSizeInformation[1];

        // dimensions
        /** @var \HDNET\Focuspoint\Service\DimensionService $service */
        $dimensionService = GeneralUtility::makeInstance('HDNET\\Focuspoint\\Service\\DimensionService');
        list($focusWidth, $focusHeight) = $dimensionService->getFocusWidthAndHeight($width, $height, $ratio);
        $cropMode = $dimensionService->getCropMode($width, $height, $ratio);
        list($sourceX, $sourceY) = $dimensionService->calculateSourcePosition($cropMode, $width, $height, $focusWidth,
            $focusHeight, $focusPointX, $focusPointY);

        // generate image
        $this->createCropImageGifBuilder($absoluteImageName, $focusWidth, $focusHeight, $sourceX, $sourceY,
            $absoluteTempImageName);
        return $tempImageName;
    }

    /**
     * Create the crop image (GifBuilder)
     *
     * @param $absoluteImageName
     * @param $focusWidth
     * @param $focusHeight
     * @param $sourceX
     * @param $sourceY
     * @param $absoluteTempImageName
     */
    protected function createCropImageGifBuilder(
        $absoluteImageName,
        $focusWidth,
        $focusHeight,
        $sourceX,
        $sourceY,
        $absoluteTempImageName
    ) {
        $size = getimagesize($absoluteImageName);
        $configuration = array(
            'format' => strtolower(PathUtility::pathinfo($absoluteImageName, PATHINFO_EXTENSION)),
            'XY' => $size[0] . ',' . $size[1],
            'transparentBackground' => '1',
            '10' => 'IMAGE',
            '10.' => array(
                'file' => $absoluteImageName,
                'file.' => array(
                    'quality' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality'],
                    'width' => $size[0],
                    'height' => $size[1],
                ),
            ),
            '20' => 'CROP',
            '20.' => array(
                'crop' => $sourceX . ',' . $sourceY . ',' . $focusWidth . ',' . $focusHeight,
            ),
        );

        /** @var \TYPO3\CMS\Frontend\Imaging\GifBuilder $gifBuilder */
        $gifBuilder = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Imaging\\GifBuilder');
        $gifBuilder->init();
        $gifBuilder->start($configuration, array());
        $gifBuilder->createTempSubDir('focuscrop/');
        $gifBuilder->make();
        $gifBuilder->output($absoluteTempImageName);
        $gifBuilder->destroy();
    }
}
