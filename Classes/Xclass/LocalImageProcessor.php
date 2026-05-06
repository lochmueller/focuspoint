<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Xclass;

use HDNET\Focuspoint\Service\DimensionService;
use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Resource\Processing\TaskInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Local image processor (overwrite).
 */
class LocalImageProcessor extends \TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor
{
    /**
     * If set to true, the process is running and no additional calculation is needed.
     */
    protected static bool $deepCheck = false;

    protected DimensionService $dimensionService;

    protected FocusCropService $focusCropService;

    public function __construct()
    {
        $this->dimensionService = GeneralUtility::makeInstance(DimensionService::class);
        $this->focusCropService = GeneralUtility::makeInstance(FocusCropService::class);
    }

    /**
     * Processing the focus point crop (fallback to LocalImageProcessor).
     *
     * Both 'Preview' and 'CropScaleMask' tasks route through this method,
     * so we only intercept 'CropScaleMask'.
     */
    public function processTask(TaskInterface $task): void
    {
        if ($task->getName() !== 'CropScaleMask') {
            parent::processTask($task);
            return;
        }

        $configuration = $task->getConfiguration();
        $crop = isset($configuration['crop']) ? json_decode((string) $configuration['crop']) : null;
        if ($crop instanceof \stdClass && isset($crop->x)) {
            // if crop is enable release the process
            parent::processTask($task);
            return;
        }

        $sourceFile = $task->getSourceFile();

        try {
            if (false === self::$deepCheck) {
                self::$deepCheck = true;
                $ratio = $this->getCurrentRatioConfiguration();
                $this->dimensionService->getRatio($ratio);

                $newFile = $this->focusCropService->getCroppedImageSrcByFile($sourceFile, $ratio);
                if (null === $newFile) {
                    self::$deepCheck = false;
                    parent::processTask($task);
                    return;
                }
                $file = GeneralUtility::makeInstance(ResourceFactory::class)
                    ->retrieveFileOrFolderObject($newFile)
                ;

                $targetFile = $task->getTargetFile();
                ObjectAccess::setProperty($targetFile, 'originalFile', $file, true);
                ObjectAccess::setProperty($targetFile, 'originalFileSha1', $file->getSha1(), true);
                // @todo double check if this line is needed
                // ObjectAccess::setProperty($targetFile, 'storage', $file->getStorage(), true);
                ObjectAccess::setProperty($task, 'sourceFile', $file, true);
                ObjectAccess::setProperty($task, 'targetFile', $targetFile, true);
            }
        } catch (\Exception $ex) {
            // not handled
        }
        self::$deepCheck = false;

        parent::processTask($task);
    }

    /**
     * Find the current ratio configuration.
     *
     * @throws \Exception
     */
    protected function getCurrentRatioConfiguration(): string
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        $cObj = $request?->getAttribute('currentContentObject');
        $currentRecord = $cObj instanceof ContentObjectRenderer ? (string) $cObj->currentRecord : '';

        if (empty($currentRecord)) {
            throw new \Exception('No current record found on the request', 12366);
        }

        $parts = GeneralUtility::trimExplode(':', $currentRecord);
        if (2 !== \count($parts)) {
            throw new \Exception('Invalid count of current record parts', 12367);
        }
        if ('tt_content' !== $parts[0]) {
            throw new \Exception('Invalid part 0. part 0 have to be tt_content', 127383);
        }
        $record = BackendUtility::getRecord($parts[0], (int) $parts[1]);
        if (!isset($record['image_ratio'])) {
            throw new \Exception('No image_ratio found in the current record', 324672);
        }

        return trim((string) $record['image_ratio']);
    }
}