<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Service;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\Imaging\GifBuilder;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;

class CropService extends AbstractService
{
    /**
     * Create the crop version of the image.
     */
    public function createImage(
        string $absoluteImageName,
        int $focusWidth,
        int $focusHeight,
        int $sourceX,
        int $sourceY,
        string $absoluteTempImageName
    ): void {
        $fileExtension = mb_strtolower(PathUtility::pathinfo($absoluteImageName, PATHINFO_EXTENSION));
        $function = $this->getFunctionName($fileExtension);
        $function = 'cropVia' . $function;
        $this->{$function}(
            $absoluteImageName,
            $focusWidth,
            $focusHeight,
            $sourceX,
            $sourceY,
            $absoluteTempImageName
        );
    }

    /**
     * Get the graphical function by file extension.
     */
    protected function getFunctionName(string $fileExtension): string
    {
        $validFunctions = [
            'GraphicalFunctions',
            'GifBuilder',
            'ImageMagick',
        ];
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('focuspoint');
        $functionConfiguration = $configuration['imageFunctionConfiguration'] ?? 'png:cropViaGifBuilder;*:GraphicalFunctions';
        $parts = GeneralUtility::trimExplode(';', $functionConfiguration, true);
        foreach ($parts as $part) {
            [$extensions, $function] = GeneralUtility::trimExplode(':', $part, true);
            if (!\in_array($function, $validFunctions, true)) {
                continue;
            }

            $extensions = GeneralUtility::trimExplode(',', $extensions, true);
            if (\in_array($fileExtension, $extensions, true) || \in_array('*', $extensions, true)) {
                return $function;
            }
        }

        return $validFunctions[0];
    }

    /**
     * Create the crop image (ImageMagikc/Gm).
     */
    protected function cropViaImageMagick(
        string $absoluteImageName,
        int $focusWidth,
        int $focusHeight,
        int $sourceX,
        int $sourceY,
        string $absoluteTempImageName
    ): void {
        $quality = MathUtility::forceIntegerInRange($GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality'], 10, 100, 75);

        $cropCommand = $focusWidth . 'x' . $focusHeight . '+' . $sourceX . '+' . $sourceY;
        $command = CommandUtility::imageMagickCommand(
            'convert',
            '-quality ' . $quality . ' ' . $absoluteImageName . ' -crop ' . $cropCommand . '  +repage ' . $absoluteTempImageName
        );
        CommandUtility::exec($command, $out);
    }

    /**
     * Create the crop image (GifBuilder).
     */
    protected function cropViaGifBuilder(
        string $absoluteImageName,
        int $focusWidth,
        int $focusHeight,
        int $sourceX,
        int $sourceY,
        string $absoluteTempImageName
    ): void {

        // early exit if absoluteTempImage exists
        if (file_exists($absoluteTempImageName)) {
            return;
        }
        $size = getimagesize($absoluteImageName);
        $relativeImagePath = rtrim(PathUtility::getRelativePath(
            GeneralUtility::getIndpEnv('TYPO3_DOCUMENT_ROOT'),
            $absoluteImageName
        ), '/');
        // We need to pass maxWidth and maxHeight, which would otherwise fall back to 2000px, see:
        // https://github.com/TYPO3/TYPO3.CMS/blob/TYPO3_7-6/typo3/sysext/frontend/Classes/Imaging/GifBuilder.php#L367-L368
        $configuration = [
            'format' => mb_strtolower(PathUtility::pathinfo($absoluteImageName, PATHINFO_EXTENSION)),
            'XY' => $size[0] . ',' . $size[1],
            'maxWidth' => $size[0],
            'maxHeight' => $size[1],
            'transparentBackground' => '1',
            '10' => 'IMAGE',
            '10.' => [
                'file' => $relativeImagePath,
                'file.' => [
                    'quality' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality'],
                    'width' => $size[0],
                    'height' => $size[1],
                ],
            ],
            '20' => 'CROP',
            '20.' => [
                'crop' => $sourceX . ',' . $sourceY . ',' . $focusWidth . ',' . $focusHeight,
            ],
        ];
        /** @var GifBuilder $gifBuilder */
        $gifBuilder = GeneralUtility::makeInstance(GifBuilder::class);
        $gifBuilder->start($configuration, []);

        $versionService = GeneralUtility::makeInstance(Typo3Version::class);
        $processedFile = false;
        if ($versionService->getMajorVersion() < 13) {
            // TYPO3 prior v13 returns a string
            // see https://api.typo3.org/12.4/classes/TYPO3-CMS-Frontend-Imaging-GifBuilder.html#method_gifBuild
            $processedFile = $gifBuilder->gifBuild();
        } else {
            // TYPO3 from v13 up returns an imageResource
            // https://api.typo3.org/13.4/classes/TYPO3-CMS-Frontend-Imaging-GifBuilder.html#method_gifBuild
            $imageResource = $gifBuilder->gifBuild();
            if ($imageResource !== null) {
                $processedFile = $imageResource->getPublicUrl();
            }
        }
        if ($processedFile) {
            copy(Environment::getPublicPath() . '/' . $processedFile, $absoluteTempImageName);
        }
    }

    /**
     * Create the crop image (GraphicalFunctions).
     */
    protected function cropViaGraphicalFunctions(
        string $absoluteImageName,
        int $focusWidth,
        int $focusHeight,
        int $sourceX,
        int $sourceY,
        string $absoluteTempImageName
    ): void {
        $this->cropViaGifBuilder($absoluteImageName, $focusWidth, $focusHeight, $sourceX, $sourceY, $absoluteTempImageName);
    }
}
