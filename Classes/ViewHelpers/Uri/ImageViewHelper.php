<?php

/**
 * Get the URI of the cropped image
 *
 * @package Focuspoint\Service
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\ViewHelpers\Uri;

use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;

/**
 * Get the URI of the cropped image
 */
class ImageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Uri\ImageViewHelper
{

    /**
     * Initialize ViewHelper arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('ratio', 'string', 'Ratio of the image', false, '1:1');
    }

    /**
     * Resize the image (if required) and returns its path. If the image was not changed, the path will be equal to $src
     *
     * @see http://typo3.org/documentation/document-library/references/doc_core_tsref/4.2.0/view/1/5/#id4164427
     *
     * @param string $src
     * @param FileInterface|AbstractFileFolder $image
     * @param string $width width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string $height height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param integer $minWidth minimum width of the image
     * @param integer $minHeight minimum height of the image
     * @param integer $maxWidth maximum width of the image
     * @param integer $maxHeight maximum height of the image
     * @param boolean $treatIdAsReference given src argument is a sys_file_reference record
     * @param string|boolean $crop overrule cropping of image (setting to FALSE disables the cropping set in FileReference)
     * @param boolean $absolute Force absolute URL
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string path to the image
     */
    public function render(
        $src = null,
        $image = null,
        $width = null,
        $height = null,
        $minWidth = null,
        $minHeight = null,
        $maxWidth = null,
        $maxHeight = null,
        $treatIdAsReference = false,
        $crop = null,
        $absolute = false
    ) {
        return self::renderStatic([
            'src' => $src,
            'image' => $image,
            'width' => $width,
            'height' => $height,
            'minWidth' => $minWidth,
            'minHeight' => $minHeight,
            'maxWidth' => $maxWidth,
            'maxHeight' => $maxHeight,
            'treatIdAsReference' => $treatIdAsReference,
            'crop' => $crop,
            'absolute' => $absolute,
            'ratio' => $this->arguments['ratio'],
        ], $this->buildRenderChildrenClosure(), $this->renderingContext);
    }

    /**
     * @param array $arguments
     * @param callable|\Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     * @throws Exception
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
