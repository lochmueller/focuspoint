<?php
/**
 * Local crop scale mask helper (overwrite)
 *
 * @package Focuspoint\Xclass
 * @author  Tim Lochmüller
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
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Local crop scale mask helper (overwrite)
 *
 * @author Tim Lochmüller
 */
class LocalCropScaleMaskHelper extends \TYPO3\CMS\Core\Resource\Processing\LocalCropScaleMaskHelper
{

    /**
     * If set to true, the pocess is running and no addinal calculation are needed
     *
     * @var bool
     */
    protected static $deepCheck = false;

    /**
     * Dimension service
     *
     * @var DimensionService
     */
    protected $dimensionService;

    /**
     * focus crop service
     *
     * @var FocusCropService
     */
    protected $focusCropService;

    /**
     * Build up the object
     *
     * @param LocalImageProcessor $processor
     */
    public function __construct(LocalImageProcessor $processor)
    {
        $this->dimensionService = GeneralUtility::makeInstance(DimensionService::class);
        $this->focusCropService = GeneralUtility::makeInstance(FocusCropService::class);
        parent::__construct($processor);
    }

    /**
     * Processing the focus point crop (fallback to LocalCropScaleMaskHelper)
     *
     * @param TaskInterface $task
     *
     * @return array|NULL
     */
    public function process(TaskInterface $task)
    {
        $configuration = $task->getConfiguration();
        $crop = $configuration['crop'] ? json_decode($configuration['crop']) : null;
        if ($crop instanceof \stdClass && isset($crop->x)) {
            // if crop is enable release the process
            return parent::process($task);
        }

        $sourceFile = $task->getSourceFile();
        try {
            if (self::$deepCheck === false) {
                self::$deepCheck = true;
                $ratio = $this->getCurrentRatioConfiguration();
                $this->dimensionService->getRatio($ratio);

                $newFile = $this->focusCropService->getCroppedImageSrcByFile($sourceFile, $ratio);
                if ($newFile === null) {
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
        }
        self::$deepCheck = false;

        return parent::process($task);
    }

    /**
     * Find the current ratio configuration
     *
     * @return string|NULL
     */
    protected function getCurrentRatioConfiguration()
    {
        $currentRecord = $GLOBALS['TSFE']->currentRecord;
        $parts = GeneralUtility::trimExplode(':', $currentRecord);
        if (sizeof($parts) !== 2) {
            return null;
        }
        if ($parts[0] !== 'tt_content') {
            return null;
        }
        $record = BackendUtility::getRecord($parts[0], (int)$parts[1]);
        return isset($record['image_ratio']) ? trim($record['image_ratio']) : null;
    }
}
