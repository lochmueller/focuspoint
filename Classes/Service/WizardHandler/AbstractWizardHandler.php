<?php

/**
 * Abstract wizard handler
 */

namespace HDNET\Focuspoint\Service\WizardHandler;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * Abstract wizard handler
 */
abstract class AbstractWizardHandler
{

    /**
     * Check if the handler can handle the current request
     *
     * @return true
     */
    abstract public function canHandle();

    /**
     * get the arguments for same request call
     *
     * @return array
     */
    abstract public function getArguments();

    /**
     * Return the current point (between -100 and 100)
     *
     * @return array
     */
    abstract public function getCurrentPoint();

    /**
     * Set the point (between -100 and 100)
     *
     * @param int $x
     * @param int $y
     * @return void
     */
    abstract public function setCurrentPoint($x, $y);

    /**
     * Get the public URL for the current handler
     *
     * @return string
     */
    abstract public function getPublicUrl();

    /**
     * @param string $url
     *
     * @return string
     */
    protected function displayableImageUrl($url){
        if (in_array(PathUtility::pathinfo($url, PATHINFO_EXTENSION), ['tif', 'tiff'])) {
            $objectManager = new ObjectManager();
            /** @var ImageService $imageService */
            $imageService = $objectManager->get('TYPO3\\CMS\\Extbase\\Service\\ImageService');
            $image = $imageService->getImage($url, null, null);
            $processedImage = $imageService->applyProcessingInstructions($image, [
                'width' => '800',
            ]);
            $url = $imageService->getImageUri($processedImage);
        }
        return GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . ltrim($url, '/');
    }

    /**
     * Cleanup the position of both values
     *
     * @param array $position
     *
     * @return array
     */
    protected function cleanupPosition($position) {
        return [
            MathUtility::forceIntegerInRange((int)$position[0], -100, 100, 0),
            MathUtility::forceIntegerInRange((int)$position[1], -100, 100, 0)
        ];
    }

}
