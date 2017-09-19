<?php
/**
 * Calc dimensions for focus point cropping.
 *
 */

namespace HDNET\Focuspoint\Service;

use HDNET\Focuspoint\Utility\GlobalUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Calc dimensions for focus point cropping.
 *
 */
class DimensionService extends AbstractService
{
    /**
     * Crop mode. Not necessary.
     */
    const CROP_NONE = 0;

    /**
     * Crop in landscape.
     */
    const CROP_LANDSCAPE = 1;

    /**
     * Crop in portrait.
     */
    const CROP_PORTRAIT = 2;

    /**
     * Calc the ratio and check if the crop is landscape or portrait relevant.
     *
     * @param int    $width
     * @param int    $height
     * @param string $ratio  In format "1:1"
     *
     * @return int
     */
    public function getCropMode($width, $height, $ratio)
    {
        $ratio = $this->getRatio($ratio);
        $widthDiff = $width / $ratio[0];
        $heightDiff = $height / $ratio[1];
        if ($widthDiff == $heightDiff) {
            return self::CROP_NONE;
        }

        return $widthDiff > $heightDiff ? self::CROP_LANDSCAPE : self::CROP_PORTRAIT;
    }

    /**
     * Calc the focus width and height by the given ratio.
     *
     * @param int    $width
     * @param int    $height
     * @param string $ratio  In format "1:1"
     *
     * @return array
     */
    public function getFocusWidthAndHeight($width, $height, $ratio)
    {
        $width = (int) $width;
        $height = (int) $height;
        $ratio = $this->getRatio($ratio);
        $widthDiff = $width / $ratio[0];
        $heightDiff = $height / $ratio[1];

        if ($widthDiff < $heightDiff) {
            return [
                $width,
                (int) ceil($widthDiff / $heightDiff * $height),
            ];
        } elseif ($widthDiff > $heightDiff) {
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
     *
     * @param int $cropMode
     * @param int $width
     * @param int $height
     * @param int $focusWidth
     * @param int $focusHeight
     * @param int $focusPointX
     * @param int $focusPointY
     *
     * @return array
     */
    public function calculateSourcePosition(
        $cropMode,
        $width,
        $height,
        $focusWidth,
        $focusHeight,
        $focusPointX,
        $focusPointY
    ) {
        if (self::CROP_PORTRAIT == $cropMode) {
            return array_reverse($this->getShiftedFocusAreaPosition($height, $focusHeight, $focusPointY, true));
        } elseif (self::CROP_LANDSCAPE == $cropMode) {
            return $this->getShiftedFocusAreaPosition($width, $focusWidth, $focusPointX);
        }

        return [
            0,
            0,
        ];
    }

    /**
     * Calc the shifted focus area.
     *
     * @param int  $length
     * @param int  $focusLength
     * @param int  $focusPosition
     * @param bool $invertScala
     *
     * @return array
     */
    protected function getShiftedFocusAreaPosition($length, $focusLength, $focusPosition, $invertScala = false)
    {
        $halfWidth = $length / 2;
        $pixelPosition = (int) floor($halfWidth * $focusPosition / 100 + $halfWidth);
        if ($invertScala) {
            $pixelPosition = $length - $pixelPosition;
        }
        $crop1 = (int) ($pixelPosition - floor($focusLength / 2));
        $crop2 = (int) ($crop1 + $focusLength);
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
     * get the shifted focus point point position
     * for e.g. Frontend handling of the new created image.
     *
     * @param int    $imgWidth
     * @param int    $imgHeight
     * @param int    $focusX
     * @param int    $focusY
     * @param string $ratio
     *
     * @return array
     */
    public function getShiftedFocusPointPosition($imgWidth, $imgHeight, $focusX, $focusY, $ratio)
    {
        $halfWidth = $imgWidth / 2;
        $halfHeight = $imgHeight / 2;

        $realFocusX = $halfWidth + ($focusX / 100 * $halfWidth);
        $realFocusY = $halfHeight - ($focusY / 100 * $halfHeight);

        list($focusWidth, $focusHeight) = $this->getFocusWidthAndHeight($imgWidth, $imgHeight, $ratio);

        list($sourceX, $sourceY) = $this->calculateSourcePosition(
            $imgWidth,
            $imgHeight,
            $focusWidth,
            $focusHeight,
            $focusX,
            $focusY,
            $ratio
        );

        $newHalfWidth = $focusWidth / 2;
        $newHalfHeight = $focusHeight / 2;

        $newRealFocusX = $realFocusX - $sourceX;
        $newRealFocusY = $realFocusY - $sourceY;

        $newFocusX = ($newRealFocusX - $newHalfWidth) * 100 / ($newHalfWidth);
        $newFocusY = ($newHalfHeight - $newRealFocusY) * 100 / ($newHalfHeight);
        $newFocusX = (int) round($newFocusX, 0);
        $newFocusY = (int) round($newFocusY, 0);

        return [$newFocusX, $newFocusY];
    }

    /**
     * Check the ratio and create an array.
     *
     * @param string $ratio
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getRatio($ratio)
    {
        $ratio = $this->mapDatabaseRatio($ratio);
        $ratio = explode(':', $ratio);
        if (2 !== sizeof($ratio)) {
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
     * Map the given ratio to the DB table.
     *
     * @param string $ratio
     *
     * @return string
     */
    protected function mapDatabaseRatio($ratio)
    {
        $databaseConnection = GlobalUtility::getDatabaseConnection();
        if (!is_object($databaseConnection)) {
            return $ratio;
        }
        $table = 'tx_focuspoint_domain_model_dimension';
        $row = $databaseConnection->exec_SELECTgetSingleRow(
            '*',
            $table,
            'identifier=' . $databaseConnection->fullQuoteStr($ratio, $table)
        );
        if (isset($row['dimension'])) {
            return $row['dimension'];
        }

        return $ratio;
    }
}
