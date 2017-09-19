<?php
/**
 * Crop images.
 *
 */

namespace HDNET\Focuspoint\Service;

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\Imaging\GifBuilder;

/**
 * Crop images.
 */
class CropService extends AbstractService
{
    /**
     * Create the crop version of the image.
     *
     * @param string $absoluteImageName
     * @param int    $focusWidth
     * @param int    $focusHeight
     * @param int    $sourceX
     * @param int    $sourceY
     * @param string $absoluteTempImageName
     */
    public function createImage(
        $absoluteImageName,
        $focusWidth,
        $focusHeight,
        $sourceX,
        $sourceY,
        $absoluteTempImageName
    ) {
        $fileExtension = strtolower(PathUtility::pathinfo($absoluteImageName, PATHINFO_EXTENSION));

        // @todo otpion for the default crop mechanism
        // cropViaImageMagick

        $function = 'cropViaGraphicalFunctions';
        if ('png' === $fileExtension) {
            $function = 'cropViaGifBuilder';
        }

        $function = 'cropViaImageMagick';

        $this->$function(
            $absoluteImageName,
            $focusWidth,
            $focusHeight,
            $sourceX,
            $sourceY,
            $absoluteTempImageName
        );
    }

    /**
     * Create the crop image (ImageMagikc/Gm).
     *
     * @param string $absoluteImageName
     * @param int    $focusWidth
     * @param int    $focusHeight
     * @param int    $sourceX
     * @param int    $sourceY
     * @param string $absoluteTempImageName
     */
    protected function cropViaImageMagick(
        $absoluteImageName,
        $focusWidth,
        $focusHeight,
        $sourceX,
        $sourceY,
        $absoluteTempImageName
    ) {
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
     *
     * @param string $absoluteImageName
     * @param int    $focusWidth
     * @param int    $focusHeight
     * @param int    $sourceX
     * @param int    $sourceY
     * @param string $absoluteTempImageName
     */
    protected function cropViaGifBuilder(
        $absoluteImageName,
        $focusWidth,
        $focusHeight,
        $sourceX,
        $sourceY,
        $absoluteTempImageName
    ) {
        $size = getimagesize($absoluteImageName);
        $relativeImagePath = rtrim(PathUtility::getRelativePath(
            GeneralUtility::getIndpEnv('TYPO3_DOCUMENT_ROOT'),
            $absoluteImageName
        ), '/');
        // We need to pass maxWidth and maxHeight, which would otherwise fall back to 2000px, see:
        // https://github.com/TYPO3/TYPO3.CMS/blob/TYPO3_7-6/typo3/sysext/frontend/Classes/Imaging/GifBuilder.php#L367-L368
        $configuration = [
            'format' => strtolower(PathUtility::pathinfo($absoluteImageName, PATHINFO_EXTENSION)),
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
        $gifBuilder->init();
        $gifBuilder->start($configuration, []);
        $gifBuilder->createTempSubDir('focuscrop/');
        $gifBuilder->make();
        $gifBuilder->output($absoluteTempImageName);
        $gifBuilder->destroy();
    }

    /**
     * Create the crop image (GraphicalFunctions).
     *
     * @param string $absoluteImageName
     * @param int    $focusWidth
     * @param int    $focusHeight
     * @param int    $sourceX
     * @param int    $sourceY
     * @param string $absoluteTempImageName
     */
    protected function cropViaGraphicalFunctions(
        $absoluteImageName,
        $focusWidth,
        $focusHeight,
        $sourceX,
        $sourceY,
        $absoluteTempImageName
    ) {
        /** @var GraphicalFunctions $graphicalFunctions */
        $graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
        $sourceImage = $graphicalFunctions->imageCreateFromFile($absoluteImageName);
        $destinationImage = imagecreatetruecolor($focusWidth, $focusHeight);

        // prevent the problem of large images result in a "Allowed memory size" error
        // we do not need the alpha layer at all, because the PNG rendered with cropViaGraphicalFunctions
        ObjectAccess::setProperty($graphicalFunctions, 'saveAlphaLayer', true, true);

        $graphicalFunctions->imagecopyresized(
            $destinationImage,
            $sourceImage,
            0,
            0,
            $sourceX,
            $sourceY,
            $focusWidth,
            $focusHeight,
            $focusWidth,
            $focusHeight
        );

        $graphicalFunctions->ImageWrite(
            $destinationImage,
            $absoluteTempImageName,
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality']
        );
    }
}
