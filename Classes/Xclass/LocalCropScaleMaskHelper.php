<?php
/**
 * Local crop scale mask helper (overwrite)
 *
 * @package Focuspoint\Xclass
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Xclass;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Resource\Processing\TaskInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Local crop scale mask helper (overwrite)
 *
 * @author Tim Lochmüller
 */
class LocalCropScaleMaskHelper extends \TYPO3\CMS\Core\Resource\Processing\LocalCropScaleMaskHelper
{

    /**
     * Dimension service
     *
     * @var \HDNET\Focuspoint\Service\DimensionService
     */
    protected $dimensionService;

    /**
     * focus crop service
     *
     * @var \HDNET\Focuspoint\Service\FocusCropService
     */
    protected $focusCropService;

    /**
     * Build up the object
     *
     * @param LocalImageProcessor $processor
     */
    public function __construct(LocalImageProcessor $processor)
    {
        $this->dimensionService = GeneralUtility::makeInstance('HDNET\\Focuspoint\\Service\\DimensionService');
        $this->focusCropService = GeneralUtility::makeInstance('HDNET\\Focuspoint\\Service\\FocusCropService');
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
        $sourceFile = $task->getSourceFile();
        try {
            $ratio = $this->getCurrentRatioConfiguration();
            $this->dimensionService->getRatio($ratio);
            $newFile = $this->focusCropService->getCroppedImageSrcByFile($sourceFile, $ratio);
            $file = ResourceFactory::getInstance()
                ->retrieveFileOrFolderObject($newFile);

            $targetFile = $task->getTargetFile();
            ObjectAccess::setProperty($targetFile, 'originalFile', $file, true);
            ObjectAccess::setProperty($targetFile, 'originalFileSha1', $file->getSha1(), true);
            ObjectAccess::setProperty($targetFile, 'storage', $file->getStorage(), true);
            ObjectAccess::setProperty($task, 'sourceFile', $file, true);
            ObjectAccess::setProperty($task, 'targetFile', $targetFile, true);
        } catch (\Exception $ex) {
        }

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
