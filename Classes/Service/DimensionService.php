<?php

declare(strict_types=1);


namespace HDNET\Focuspoint\Service;

use HDNET\Focuspoint\Domain\Repository\DimensionRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Calc dimensions for focus point cropping.
 */
class DimensionService extends AbstractService
{
    /**
     * Crop mode. Not necessary.
     */
    public const CROP_NONE = 0;

    /**
     * Crop in landscape.
     */
    public const CROP_LANDSCAPE = 1;

    /**
     * Crop in portrait.
     */
    public const CROP_PORTRAIT = 2;

    /**
     * Calc the ratio and check if the crop is landscape or portrait relevant.
     *
     * @param string $ratio In format "1:1"
     */
    public function getCropMode(int $width, int $height, string $ratio): int
    {
        $ratio = $this->getRatio($ratio);
        $widthDiff = $width / $ratio[0];
        $heightDiff = $height / $ratio[1];
        if ($widthDiff === $heightDiff) {
            return self::CROP_NONE;
        }

        return $widthDiff > $heightDiff ? self::CROP_LANDSCAPE : self::CROP_PORTRAIT;
    }

    /**
     * Calc the focus width and height by the given ratio.
     *
     * @param string $ratio In format "1:1"
     */
    public function getFocusWidthAndHeight(int $width, int $height, string $ratio): array
    {
        $ratio = $this->getRatio($ratio);
        $widthDiff = $width / $ratio[0];
        $heightDiff = $height / $ratio[1];

        if ($widthDiff < $heightDiff) {
            return [
                $width,
                (int) ceil($widthDiff / $heightDiff * $height),
            ];
        }
        if ($widthDiff > $heightDiff) {
            return [
                (int) ceil($heightDiff / $widthDiff * $width),
                $height,
            ];
        }

        return [
            $width,
            $height,
        ];
    }

    /**
     * Calculate the source X and Y position.
     */
    public function calculateSourcePosition(
        int $cropMode,
        int $width,
        int $height,
        int $focusWidth,
        int $focusHeight,
        int $focusPointX,
        int $focusPointY
    ): array {
        if (self::CROP_PORTRAIT === $cropMode) {
            return array_reverse($this->getShiftedFocusAreaPosition($height, $focusHeight, $focusPointY, true));
        }
        if (self::CROP_LANDSCAPE === $cropMode) {
            return $this->getShiftedFocusAreaPosition($width, $focusWidth, $focusPointX);
        }

        return [
            0,
            0,
        ];
    }

    /**
     * get the shifted focus point point position
     * for e.g. Frontend handling of the new created image.
     */
    public function getShiftedFocusPointPosition(int $imgWidth, int $imgHeight, int $focusX, int $focusY, string $ratio): array
    {
        $halfWidth = $imgWidth / 2;
        $halfHeight = $imgHeight / 2;

        $realFocusX = $halfWidth + ($focusX / 100 * $halfWidth);
        $realFocusY = $halfHeight - ($focusY / 100 * $halfHeight);

        [$focusWidth, $focusHeight] = $this->getFocusWidthAndHeight($imgWidth, $imgHeight, $ratio);

        [$sourceX, $sourceY] = $this->calculateSourcePosition(
            $focusWidth > $focusHeight ? self::CROP_LANDSCAPE : self::CROP_PORTRAIT,
            $imgWidth,
            $imgHeight,
            $focusWidth,
            $focusHeight,
            $focusX,
            $focusY
        );

        $newHalfWidth = $focusWidth / 2;
        $newHalfHeight = $focusHeight / 2;

        $newRealFocusX = $realFocusX - $sourceX;
        $newRealFocusY = $realFocusY - $sourceY;

        $newFocusX = ($newRealFocusX - $newHalfWidth) * 100 / $newHalfWidth;
        $newFocusY = ($newHalfHeight - $newRealFocusY) * 100 / $newHalfHeight;
        $newFocusX = (int) round($newFocusX, 0);
        $newFocusY = (int) round($newFocusY, 0);

        return [$newFocusX, $newFocusY];
    }

    /**
     * Check the ratio and create an array.
     *
     * @throws \Exception
     */
    public function getRatio(string $ratio): array
    {
        $ratio = $this->mapDatabaseRatio($ratio);
        $ratio = explode(':', $ratio);
        if (2 !== \count($ratio)) {
            throw new \Exception('Ratio have to be in the format of e.g. "1:1" or "16:9"', 1475144026);
        }

        return [
            MathUtility::canBeInterpretedAsInteger($ratio[0]) ? (int) $ratio[0] : (float) str_replace(
                ',',
                '.',
                $ratio[0]
            ),
            MathUtility::canBeInterpretedAsInteger($ratio[1]) ? (int) $ratio[1] : (float) str_replace(
                ',',
                '.',
                $ratio[1]
            ),
        ];
    }

    /**
     * Calc the shifted focus area.
     */
    protected function getShiftedFocusAreaPosition(int $length, int $focusLength, int $focusPosition, bool $invertScala = false): array
    {
        $halfWidth = $length / 2;
        $pixelPosition = (int) floor($halfWidth * $focusPosition / 100 + $halfWidth);
        if ($invertScala) {
            $pixelPosition = $length - $pixelPosition;
        }
        $crop1 = (int) ($pixelPosition - floor($focusLength / 2));
        $crop2 = $crop1 + $focusLength;
        if ($crop1 < 0) {
            $crop1 -= $crop1;
        } elseif ($crop2 > $length) {
            $diff = $crop2 - $length;
            $crop1 -= $diff;
        }

        $sourceX = $crop1;
        $sourceY = 0;

        return [
            $sourceX,
            $sourceY,
        ];
    }

    /**
     * Map the given ratio to the DB table.
     */
    protected function mapDatabaseRatio(string $ratio): string
    {
        $row = GeneralUtility::makeInstance(DimensionRepository::class)->findOneByIdentifier($ratio);
        if (isset($row['dimension'])) {
            return (string) $row['dimension'];
        }

        return $ratio;
    }
}
