<?php

declare(strict_types = 1);

/**
 * Abstract wizard handler.
 */

namespace HDNET\Focuspoint\Service\WizardHandler;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * Abstract wizard handler.
 */
abstract class AbstractWizardHandler
{
    /**
     * Check if the handler can handle the current request.
     */
    abstract public function canHandle(): bool;

    /**
     * get the arguments for same request call.
     */
    abstract public function getArguments(): array;

    /**
     * Return the current point (between -100 and 100).
     */
    abstract public function getCurrentPoint(): array;

    /**
     * Set the point (between -100 and 100).
     */
    abstract public function setCurrentPoint(int $x, int $y);

    /**
     * Get the public URL for the current handler.
     */
    abstract public function getPublicUrl(): string;

    protected function displayableImageUrl(string $url): string
    {
        if (\in_array(PathUtility::pathinfo($url, PATHINFO_EXTENSION), ['tif', 'tiff'], true)) {
            /** @var ImageService $imageService */
            $imageService = GeneralUtility::makeInstance(ImageService::class);
            $image = $imageService->getImage($url, null, false);
            $processedImage = $imageService->applyProcessingInstructions($image, [
                'width' => '800',
            ]);
            $url = $imageService->getImageUri($processedImage);
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . ltrim($url, '/');
    }

    /**
     * Cleanup the position of both values.
     */
    protected function cleanupPosition(array $position): array
    {
        return [
            MathUtility::forceIntegerInRange((int)$position[0], -100, 100, 0),
            MathUtility::forceIntegerInRange((int)$position[1], -100, 100, 0),
        ];
    }
}
