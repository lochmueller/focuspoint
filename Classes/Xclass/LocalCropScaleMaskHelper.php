<?php

/**
 * Local crop scale mask helper (overwrite).
 */

namespace HDNET\Focuspoint\Xclass;

use HDNET\Focuspoint\Service\DimensionService;
use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Resource\Processing\TaskInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Local crop scale mask helper (overwrite).
 */
class LocalCropScaleMaskHelper extends \TYPO3\CMS\Core\Resource\Processing\LocalCropScaleMaskHelper
{
    /**
     * If set to true, the pocess is running and no addinal calculation are needed.
     *
     * @var bool
     */
    protected static $deepCheck = false;

    /**
     * Dimension service.
     *
     * @var DimensionService
     */
    protected $dimensionService;

    /**
     * focus crop service.
     *
     * @var FocusCropService
     */
    protected $focusCropService;

    /**
     * Build up the object.
     */
    public function __construct()
    {
        $this->dimensionService = GeneralUtility::makeInstance(DimensionService::class);
        $this->focusCropService = GeneralUtility::makeInstance(FocusCropService::class);
    }

    /**
     * Processing the focus point crop (fallback to LocalCropScaleMaskHelper).
     *
     * @param TaskInterface $task
     *
     * @return array|null
     */
    public function process(TaskInterface $task)
    {
        $configuration = $task->getConfiguration();
        $crop = $configuration['crop'] ? \json_decode($configuration['crop']) : null;
        if ($crop instanceof \stdClass && isset($crop->x)) {
            // if crop is enable release the process
            return parent::process($task);
        }

        $sourceFile = $task->getSourceFile();
        try {
            if (false === self::$deepCheck) {
                self::$deepCheck = true;
                $ratio = $this->getCurrentRatioConfiguration();
                $this->dimensionService->getRatio($ratio);

                $newFile = $this->focusCropService->getCroppedImageSrcByFile($sourceFile, $ratio);
                if (null === $newFile) {
                    return parent::process($task);
                }
                $file = ResourceFactory::getInstance()
                    ->retrieveFileOrFolderObject($newFile);

                $targetFile = $task->getTargetFile();
                ObjectAccess::setProperty($targetFile, 'originalFile', $file, true);
                ObjectAccess::setProperty($targetFile, 'originalFileSha1', $file->getSha1(), true);
                ObjectAccess::setProperty($targetFile, 'storage', $file->getStorage(), true);
                ObjectAccess::setProperty($task, 'sourceFile', $file, true);
                ObjectAccess::setProperty($task, 'targetFile', $targetFile, true);
            }
        } catch (\Exception $ex) {
            // not handled
        }
        self::$deepCheck = false;

        return parent::process($task);
    }

    /**
     * Find the current ratio configuration.
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getCurrentRatioConfiguration(): string
    {
        $currentRecord = $GLOBALS['TSFE']->currentRecord;
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

        return \trim((string) $record['image_ratio']);
    }
}
