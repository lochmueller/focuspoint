<?php
/**
 * @todo    General file information
 *
 * @package Hdnet
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
 * @todo   General class information
 *
 * @author Tim Lochmüller
 */
class LocalCropScaleMaskHelper extends \TYPO3\CMS\Core\Resource\Processing\LocalCropScaleMaskHelper {

	/**
	 * Dimension service
	 *
	 * @var \HDNET\Focuspoint\Service\DimensionService
	 * @inject
	 */
	protected $dimensionService;

	/**
	 * focus crop service
	 *
	 * @var \HDNET\Focuspoint\Service\FocusCropService
	 * @inject
	 */
	protected $focusCropService;

	/**
	 * @param LocalImageProcessor $processor
	 */
	public function __construct(LocalImageProcessor $processor) {
		$this->dimensionService = new DimensionService();
		$this->focusCropService = new FocusCropService();
	}

	/**
	 * @param TaskInterface $task
	 *
	 * @return array|NULL
	 */
	public function process(TaskInterface $task) {
		$sourceFile = $task->getSourceFile();
		try {
			$ratio = $this->getCurrentRatioConfiguration();
			$this->dimensionService->getRatio($ratio);
			$newFile = $this->focusCropService->getCroppedImageSrcByFile($sourceFile, $ratio);
			$file = ResourceFactory::getInstance()
				->retrieveFileOrFolderObject($newFile);
			ObjectAccess::setProperty($task, 'sourceFile', $file);
		} catch (\Exception $ex) {
		}

		return parent::process($task);
	}

	/**
	 * Find the current ratio configuration
	 *
	 * @return string|NULL
	 */
	protected function getCurrentRatioConfiguration() {
		$currentRecord = $GLOBALS['TSFE']->currentRecord;
		$parts = GeneralUtility::trimExplode(':', $currentRecord);
		if (sizeof($parts) !== 2) {
			return NULL;
		}
		if ($parts[0] !== 'tt_content') {
			return NULL;
		}
		$record = BackendUtility::getRecord($parts[0], (int)$parts[1]);
		return isset($record['image_ratio']) ? trim($record['image_ratio']) : NULL;
	}
}
