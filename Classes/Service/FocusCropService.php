<?php

declare(strict_types = 1);

/**
 * Crop images via focus crop.
 */

namespace HDNET\Focuspoint\Service;

use HDNET\Focuspoint\Domain\Repository\FileStandaloneRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Crop images via focus crop.
 */
class FocusCropService extends AbstractService
{
    public const SIGNAL_tempImageCropped = 'tempImageCropped';

    /**
     * @var Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * @var string
     */
    protected $tempImageFolder;

    /**
     * get the image.
     *
     * @param $src
     * @param $image
     * @param $treatIdAsReference
     *
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     *
     * @return \TYPO3\CMS\Core\Resource\File|FileInterface|CoreFileReference|\TYPO3\CMS\Core\Resource\Folder
     */
    public function getViewHelperImage($src, $image, $treatIdAsReference)
    {
        $resourceFactory = ResourceFactory::getInstance();
        if ($image instanceof \TYPO3\CMS\Core\Resource\FileReference) {
            return $image;
        }
        if ($image instanceof FileReference) {
            return $image->getOriginalResource();
        }
        if (!MathUtility::canBeInterpretedAsInteger($src)) {
            return $resourceFactory->retrieveFileOrFolderObject($src);
        }
        if (!$treatIdAsReference) {
            return $resourceFactory->getFileObject($src);
        }

        return $resourceFactory->getFileReferenceObject($src);
    }

    /**
     * Helper function for view helpers.
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
    public function getCroppedImageSrcForViewHelper($src, $image, $treatIdAsReference, string $ratio)
    {
        $file = $this->getViewHelperImage($src, $image, $treatIdAsReference);

        return $this->getCroppedImageSrcByFile($file, $ratio);
    }

    /**
     * Get the cropped image.
     *
     * @param string $fileReference
     *
     * @return string The new filename
     */
    public function getCroppedImageSrcByFileReference($fileReference, string $ratio)
    {
        if ($fileReference instanceof FileReference) {
            $fileReference = $fileReference->getOriginalResource();
        }
        if ($fileReference instanceof CoreFileReference) {
            return $this->getCroppedImageSrcByFile($fileReference->getOriginalFile(), $ratio);
        }

        throw new \InvalidArgumentException('The given argument is not a valid file reference', 1475144027);
    }

    /**
     * Get the cropped image by File Object.
     *
     * @return string The new filename
     */
    public function getCroppedImageSrcByFile(FileInterface $file, string $ratio)
    {
        $result = $this->getCroppedImageSrcBySrc(
            $file->getForLocalProcessing(false),
            $ratio,
            (int)$file->getProperty('focus_point_x'),
            (int)$file->getProperty('focus_point_y')
        );
        if ('' === $result) {
            return $file->getPublicUrl();
        }

        return $result;
    }

    /**
     * Get the cropped image by src.
     *
     * @param string $src Relative file name
     *
     * @return string The new filename
     */
    public function getCroppedImageSrcBySrc(string $src, string $ratio, int $x, int $y): string
    {
        $absoluteImageName = GeneralUtility::getFileAbsFileName($src);
        if (!is_file($absoluteImageName)) {
            return '';
        }

        /** @var \TYPO3\CMS\Core\Http\NormalizedParams $params */
        $params = $GLOBALS['TYPO3_REQUEST']->getAttribute('normalizedParams');
        $docRoot = rtrim($params->getDocumentRoot(), '/') . '/';
        $relativeSrc = str_replace($docRoot, '', $absoluteImageName);
        $focusPointX = MathUtility::forceIntegerInRange((int)$x, -100, 100, 0);
        $focusPointY = MathUtility::forceIntegerInRange((int)$y, -100, 100, 0);

        if (0 === $focusPointX && 0 === $focusPointY) {
            $row = GeneralUtility::makeInstance(FileStandaloneRepository::class)->findOneByRelativeFilePath($relativeSrc);
            if (isset($row['focus_point_x'])) {
                $focusPointX = MathUtility::forceIntegerInRange((int)$row['focus_point_x'], -100, 100, 0);
                $focusPointY = MathUtility::forceIntegerInRange((int)$row['focus_point_y'], -100, 100, 0);
            }
        }

        $tempImageFolder = $this->getTempImageFolder();
        $tempImageName = $this->generateTempImageName($absoluteImageName, $ratio, $focusPointX, $focusPointY);
        $tempImageName = $tempImageFolder . $tempImageName;

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
        /** @var DimensionService $dimensionService */
        $dimensionService = GeneralUtility::makeInstance(DimensionService::class);
        list($focusWidth, $focusHeight) = $dimensionService->getFocusWidthAndHeight($width, $height, $ratio);

        $cropMode = $dimensionService->getCropMode($width, $height, $ratio);
        list($sourceX, $sourceY) = $dimensionService->calculateSourcePosition(
            $cropMode,
            $width,
            $height,
            $focusWidth,
            $focusHeight,
            $focusPointX,
            $focusPointY
        );

        $cropService = GeneralUtility::makeInstance(CropService::class);
        $cropService->createImage(
            $absoluteImageName,
            $focusWidth,
            $focusHeight,
            $sourceX,
            $sourceY,
            $absoluteTempImageName
        );

        $this->emitTempImageCropped($tempImageName);

        return $tempImageName;
    }

    /**
     * Emit tempImageCropped signal.
     *
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function emitTempImageCropped(string $tempImageName): void
    {
        $this->getSignalSlotDispatcher()->dispatch(__CLASS__, self::SIGNAL_tempImageCropped, [$tempImageName]);
    }

    /**
     * Get the SignalSlot dispatcher.
     *
     * @return Dispatcher
     */
    protected function getSignalSlotDispatcher()
    {
        if (!isset($this->signalSlotDispatcher)) {
            $this->signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        }

        return $this->signalSlotDispatcher;
    }

    /**
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function generateTempImageName(string $absoluteImageName, string $ratio, int $focusPointX, int $focusPointY): string
    {
        $name = '';

        list($name) = $this->getSignalSlotDispatcher()->dispatch(__CLASS__, __FUNCTION__, [
            $name,
            $absoluteImageName,
            $ratio,
            $focusPointX,
            $focusPointY,
        ]);

        if ($name) {
            return $name;
        }

        $hash = \function_exists('sha1_file') ? sha1_file($absoluteImageName) : md5_file($absoluteImageName);
        $name = $hash . '-fp-' . preg_replace(
            '/[^0-9a-z-]/',
            '-',
            $ratio
        ) . '-' . $focusPointX . '-' . $focusPointY . '.' . PathUtility::pathinfo(
            $absoluteImageName,
            PATHINFO_EXTENSION
        );

        return preg_replace('/--+/', '-', $name);
    }

    /**
     * Return the folder for generated images.
     *
     * @return string Path relative to PATH_site
     */
    protected function getTempImageFolder(): string
    {
        if (null === $this->tempImageFolder) {
            // get extension Configuration set from Extension Manager
            $foucspointConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('focuspoint');
            if (isset($foucspointConfiguration['tempImageFolder'])) {
                $this->tempImageFolder = $foucspointConfiguration['tempImageFolder'];
            } else {
                $this->tempImageFolder = 'typo3temp/focuscrop/';
            }
        }

        return $this->tempImageFolder;
    }
}
