<?php
/**
 * Processing function
 *
 * @package Focuspoint\Slots
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Slots;

use HDNET\Autoloader\SingletonInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\Service\FileProcessingService;

/**
 * Processing function
 *
 * @author Tim Lochmüller
 */
class FileProcessing implements SingletonInterface {

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
	 * Handle the custom processing of files
	 *
	 * @param FileProcessingService $fileProcessingService
	 * @param                       $driver
	 * @param                       $processedFile
	 * @param                       $file
	 * @param                       $context
	 * @param                       $configuration
	 *
	 * @signalClass \TYPO3\CMS\Core\Resource\ResourceStorage
	 * @signalName preFileProcess
	 * @return array|void
	 */
	public function customFocusProcess(FileProcessingService $fileProcessingService, $driver, $processedFile, $file, $context, $configuration) {
		try {
			return;
			$this->dimensionService->getRatio($configuration['noScale']);
			$newFile = $this->focusCropService->getCroppedImageSrcByFile($file, $configuration['noScale']);
			$file = ResourceFactory::getInstance()
				->retrieveFileOrFolderObject($newFile);
			$configuration['noScale'] = NULL;
			return array(
				$fileProcessingService,
				$driver,
				$processedFile,
				$file,
				$context,
				$configuration
			);
		} catch (\Exception $ex) {
			return;
		}
	}
}
