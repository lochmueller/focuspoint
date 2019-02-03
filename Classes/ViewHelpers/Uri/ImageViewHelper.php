<?php

/**
 * Get the URI of the cropped image.
 */

namespace HDNET\Focuspoint\ViewHelpers\Uri;

use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Get the URI of the cropped image.
 */
class ImageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Uri\ImageViewHelper
{
    /**
     * Initialize ViewHelper arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('ratio', 'string', 'Ratio of the image', false, '1:1');
    }

    /**
     * Default render method - simply calls renderStatic() with a
     * prepared set of arguments.
     *
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     *
     * @return string Rendered string
     *
     * @api
     */
    public function render()
    {
        return static::renderStatic(
            $this->arguments,
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array                     $arguments
     * @param callable|\Closure         $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        /** @var FocusCropService $service */
        $service = GeneralUtility::makeInstance(FocusCropService::class);
        $arguments['src'] = $service->getCroppedImageSrcForViewHelper(
            $arguments['src'],
            $arguments['image'],
            $arguments['treatIdAsReference'],
            $arguments['ratio']
        );
        $arguments['image'] = null;
        $arguments['treatIdAsReference'] = false;

        return \TYPO3\CMS\Fluid\ViewHelpers\Uri\ImageViewHelper::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
    }
}
