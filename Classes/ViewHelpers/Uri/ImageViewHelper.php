<?php

declare(strict_types=1);

/**
 * Get the URI of the cropped image.
 */

namespace HDNET\Focuspoint\ViewHelpers\Uri;

use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ImageInterface;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Get the URI of the cropped image.
 */
class ImageViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('src', 'string', 'src', false, '');
        $this->registerArgument('treatIdAsReference', 'bool', 'given src argument is a sys_file_reference record', false, false);
        $this->registerArgument('image', 'object', 'image');
        $this->registerArgument('crop', 'string|bool', 'overrule cropping of image (setting to FALSE disables the cropping set in FileReference)');
        $this->registerArgument('cropVariant', 'string', 'select a cropping variant, in case multiple croppings have been specified or stored in FileReference', false, 'default');
        $this->registerArgument('fileExtension', 'string', 'Custom file extension to use');

        $this->registerArgument('width', 'string', 'width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('height', 'string', 'height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('minWidth', 'int', 'minimum width of the image');
        $this->registerArgument('minHeight', 'int', 'minimum height of the image');
        $this->registerArgument('maxWidth', 'int', 'maximum width of the image');
        $this->registerArgument('maxHeight', 'int', 'maximum height of the image');
        $this->registerArgument('absolute', 'bool', 'Force absolute URL', false, false);
        $this->registerArgument('base64', 'bool', 'Return a base64 encoded version of the image', false, false);

        // EXT:focuspoint
        $this->registerArgument('ratio', 'string', 'Ratio of the image', false, '1:1');
    }

    /**
     * @return string
     * @throws FileDoesNotExistException
     * @throws ResourceDoesNotExistException
     */
    public function render(): string
    {
        /** @var FocusCropService $service */
        $service = GeneralUtility::makeInstance(FocusCropService::class);
        $this->arguments['src'] = $service->getCroppedImageSrcForViewHelper(
            $this->arguments['src'],
            $this->arguments['image'],
            $this->arguments['treatIdAsReference'],
            $this->arguments['ratio']
        );

        $this->arguments['image'] = null;
        $this->arguments['treatIdAsReference'] = false;

        /** @var ImageService $imageService */
        $imageService = GeneralUtility::makeInstance(ImageService::class);
        $image = $imageService->getImage($this->arguments['src'], $this->arguments['image'], $this->arguments['treatIdAsReference']);
        $processingInstructions = [
            'width' => $this->arguments['width'],
            'height' => $this->arguments['height'],
            'minWidth' => $this->arguments['minWidth'],
            'minHeight' => $this->arguments['minHeight'],
            'maxWidth' => $this->arguments['maxWidth'],
            'maxHeight' => $this->arguments['maxHeight'],
            'crop' => $this->arguments['crop'],
        ];
        if (!empty($this->arguments['fileExtension'] ?? '')) {
            $processingInstructions['fileExtension'] = $this->arguments['fileExtension'];
        }
        $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);

        if ($this->arguments['base64']) {
            return 'data:' . $processedImage->getMimeType() . ';base64,' . base64_encode($processedImage->getContents());
        }
        return $imageService->getImageUri($processedImage, $this->arguments['absolute']);
    }

    protected function getImageService(): ImageService
    {
        return GeneralUtility::makeInstance(ImageService::class);
    }
}
