<?php

/**
 * Override the ViewHelper with ratio.
 */

namespace HDNET\Focuspoint\ViewHelpers;

use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Override the ViewHelper with ratio.
 */
class ImageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper
{
    /**
     * Initialize ViewHelper arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('ratio', 'string', 'Ratio of the image', false, '1:1');
        $this->registerArgument('realCrop', 'boolean', 'Crop the image in real', false, true);
        $this->registerArgument('additionalClassDiv', 'string', 'Additional class for focus point div', false, '');
    }

    /**
     * Resize a given image (if required) and renders the respective img tag.
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     *
     * @return string Rendered tag
     */
    public function render()
    {
        /** @var FocusCropService $service */
        $service = GeneralUtility::makeInstance(FocusCropService::class);
        $internalImage = null;
        try {
            $internalImage = $service->getViewHelperImage($this->arguments['src'], $this->arguments['image'], $this->arguments['treatIdAsReference']);
            if ($this->arguments['realCrop'] && $internalImage instanceof FileInterface) {
                $this->arguments['src'] = $service->getCroppedImageSrcByFile($internalImage, $this->arguments['ratio']);
                $this->arguments['treatIdAsReference'] = false;
                $this->arguments['image'] = null;
            }
        } catch (\Exception $ex) {
            $this->arguments['realCrop'] = true;
        }

        try {
            parent::render();
        } catch (\Exception $ex) {
            return 'Missing image!';
        }

        if ($this->arguments['realCrop']) {
            return $this->tag->render();
        }

        // Ratio calculation
        if (null !== $internalImage) {
            $focusPointY = $internalImage->getProperty('focus_point_y');
            $focusPointX = $internalImage->getProperty('focus_point_x');

            $additionalClassDiv = 'focuspoint';
            if (!empty($this->arguments['additionalClassDiv'])) {
                $additionalClassDiv .= ' ' . $this->arguments['additionalClassDiv'];
            }

            $focusTag = '<div class="' . $additionalClassDiv . '" data-image-imageSrc="' . $this->tag->getAttribute('src') . '" data-focus-x="' . ($focusPointX / 100) . '" data-focus-y="' . ($focusPointY / 100) . '" data-image-w="' . $this->tag->getAttribute('width') . '" data-image-h="' . $this->tag->getAttribute('height') . '">';

            return $focusTag . $this->tag->render() . '</div>';
        }

        return 'Missing internal image!';
    }
}
