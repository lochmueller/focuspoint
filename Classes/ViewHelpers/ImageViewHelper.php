<?php

declare(strict_types=1);

/**
 * Override the ViewHelper with ratio.
 */

namespace HDNET\Focuspoint\ViewHelpers;

use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Override the ViewHelper with ratio.
 */
class ImageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper
{
    /**
     * Initialize ViewHelper arguments.
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('ratio', 'string', 'Ratio of the image', false, '1:1');
        $this->registerArgument('realCrop', 'boolean', 'Crop the image in real', false, true);
        $this->registerArgument('additionalClassDiv', 'string', 'Additional class for focus point div', false, '');
    }

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
            $metadata = null;
            if ($internalImage instanceof FileReference) {
                $metadata = $internalImage->getOriginalFile()->getMetaData();
            }
            if ($internalImage instanceof File) {
                $metadata = $internalImage->getMetaData();
            }
            $focusPointY = $internalImage->getProperty('focus_point_y') ?: $metadata['focus_point_y'] ?? 0;
            $focusPointX = $internalImage->getProperty('focus_point_x') ?: $metadata['focus_point_x'] ?? 0;

            $additionalClassDiv = 'focuspoint';
            if (!empty($this->arguments['additionalClassDiv'])) {
                $additionalClassDiv .= ' '.$this->arguments['additionalClassDiv'];
            }

            $focusTag = '<div class="'.$additionalClassDiv.'" data-image-imageSrc="'.$this->tag->getAttribute('src').'" data-focus-x="'.($focusPointX / 100).'" data-focus-y="'.($focusPointY / 100).'" data-image-w="'.$this->tag->getAttribute('width').'" data-image-h="'.$this->tag->getAttribute('height').'">';

            return $focusTag.$this->tag->render().'</div>';
        }

        return 'Missing internal image!';
    }
}
