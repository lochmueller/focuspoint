<?php

declare(strict_types=1);



namespace HDNET\Focuspoint\Service;

use HDNET\Focuspoint\Domain\Repository\FileStandaloneRepository;
use HDNET\Focuspoint\Event\PostCroppedImageSrcBySrcEvent;
use HDNET\Focuspoint\Event\PreGenerateTempImageNameEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * Crop images via focus crop.
 */
class FocusCropService extends AbstractService
{
    /**
     * @var string
     */
    protected $tempImageFolder;

    public function __construct(
        protected EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * get the image.
     *
     * @param mixed $src
     * @param mixed $image
     * @param mixed $treatIdAsReference
     *
     * @return CoreFileReference|FileInterface|\TYPO3\CMS\Core\Resource\File|\TYPO3\CMS\Core\Resource\Folder
     *
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     */
    public function getViewHelperImage($src, $image, $treatIdAsReference)
    {
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
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
     * @param mixed $src
     * @param mixed $image
     * @param mixed $treatIdAsReference
     *
     * @return string
     *
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
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
            (int) $file->getProperty('focus_point_x'),
            (int) $file->getProperty('focus_point_y')
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
        $docRoot = rtrim($params->getDocumentRoot(), '/').'/';
        $relativeSrc = str_replace($docRoot, '', $absoluteImageName);
        $focusPointX = MathUtility::forceIntegerInRange((int) $x, -100, 100, 0);
        $focusPointY = MathUtility::forceIntegerInRange((int) $y, -100, 100, 0);

        // if image is SVG simply return its relative path we can not crop it
        if ('image/svg+xml' === mime_content_type($absoluteImageName)) {
            return $relativeSrc;
        }

        if (0 === $focusPointX && 0 === $focusPointY) {
            $row = GeneralUtility::makeInstance(FileStandaloneRepository::class)->findOneByRelativeFilePath($relativeSrc);
            if (isset($row['focus_point_x'])) {
                $focusPointX = MathUtility::forceIntegerInRange((int) $row['focus_point_x'], -100, 100, 0);
                $focusPointY = MathUtility::forceIntegerInRange((int) $row['focus_point_y'], -100, 100, 0);
            }
        }

        $tempImageFolder = $this->getTempImageFolder();
        $tempImageName = $this->generateTempImageName($absoluteImageName, $ratio, $focusPointX, $focusPointY);
        $tempImageName = $tempImageFolder.$tempImageName;

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
        [$focusWidth, $focusHeight] = $dimensionService->getFocusWidthAndHeight($width, $height, $ratio);

        $cropMode = $dimensionService->getCropMode($width, $height, $ratio);
        [$sourceX, $sourceY] = $dimensionService->calculateSourcePosition(
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

        $event = $this->eventDispatcher->dispatch(new PostCroppedImageSrcBySrcEvent($tempImageName));

        return $event->getTempImageName();
    }

    protected function generateTempImageName(string $absoluteImageName, string $ratio, int $focusPointX, int $focusPointY): string
    {
        $event = $this->eventDispatcher->dispatch(new PreGenerateTempImageNameEvent(
            '',
            $absoluteImageName,
            $ratio,
            $focusPointX,
            $focusPointY
        ));

        if ($event->getName()) {
            return $event->getName();
        }

        $hash = \function_exists('sha1_file') ? sha1_file($absoluteImageName) : md5_file($absoluteImageName);
        $name = $hash.'-fp-'.preg_replace(
            '/[^0-9a-z-]/',
            '-',
            $ratio
        ).'-'.$focusPointX.'-'.$focusPointY.'.'.PathUtility::pathinfo(
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
