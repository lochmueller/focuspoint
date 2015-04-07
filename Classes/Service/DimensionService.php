<?php
/**
 * Calc dimensions for focus point cropping
 *
 * @package Focuspoint\Service
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Calc dimensions for focus point cropping
 *
 * @author Tim Lochmüller
 */
class DimensionService extends AbstractService {

	/**
	 * Crop mode. Not necessary
	 */
	const CROP_NONE = 0;

	/**
	 * Crop in landscape
	 */
	const CROP_LANDSCAPE = 1;

	/**
	 * Crop in portrait
	 */
	const CROP_PORTRAIT = 2;

	/**
	 * Calc the ratio and check if the crop is landscape or portrait relevant
	 *
	 * @param int    $width
	 * @param int    $height
	 * @param string $ratio In format "1:1"
	 *
	 * @return int
	 */
	public function getCropMode($width, $height, $ratio) {
		$ratio = $this->getRatio($ratio);
		$widthDiff = $width / $ratio[0];
		$heightDiff = $height / $ratio[1];
		if ($widthDiff == $heightDiff) {
			return self::CROP_NONE;
		}
		return $widthDiff > $heightDiff ? self::CROP_LANDSCAPE : self::CROP_PORTRAIT;
	}

	/**
	 * Calc the focus width and height by the given ratio
	 *
	 * @param int    $width
	 * @param int    $height
	 * @param string $ratio In format "1:1"
	 *
	 * @return array
	 */
	public function getFocusWidthAndHeight($width, $height, $ratio) {
		$width = (int)$width;
		$height = (int)$height;
		$ratio = $this->getRatio($ratio);
		$widthDiff = $width / $ratio[0];
		$heightDiff = $height / $ratio[1];

		if ($widthDiff < $heightDiff) {
			return array(
				$width,
				(int)ceil($widthDiff / $heightDiff * $height)
			);
		} elseif ($widthDiff > $heightDiff) {
			return array(
				(int)ceil($heightDiff / $widthDiff * $width),
				$height
			);
		}
		return array(
			$width,
			$height
		);
	}

	/**
	 * Calculate the source X and Y position
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
	public function calculateSourcePosition($cropMode, $width, $height, $focusWidth, $focusHeight, $focusPointX, $focusPointY) {
		if ($cropMode == DimensionService::CROP_PORTRAIT) {
			return array_reverse($this->getShiftedFocusAreaPosition($height, $focusHeight, $focusPointY, TRUE));
		} elseif ($cropMode == DimensionService::CROP_LANDSCAPE) {
			return $this->getShiftedFocusAreaPosition($width, $focusWidth, $focusPointX);
		}
		return array(
			0,
			0
		);
	}

	/**
	 * Calc the shifted focus area
	 *
	 * @param int  $length
	 * @param int  $focusLength
	 * @param int  $focusPosition
	 * @param bool $invertScala
	 *
	 * @return array
	 */
	protected function getShiftedFocusAreaPosition($length, $focusLength, $focusPosition, $invertScala = FALSE) {
		$halfWidth = $length / 2;
		$pixelPosition = (int)floor($halfWidth * $focusPosition / 100 + $halfWidth);
		if ($invertScala) {
			$pixelPosition = $length - $pixelPosition;
		}
		$crop1 = (int)($pixelPosition - floor($focusLength / 2));
		$crop2 = (int)($crop1 + $focusLength);
		if ($crop1 < 0) {
			$crop1 -= $crop1;
		} elseif ($crop2 > $length) {
			$diff = $crop2 - $length;
			$crop1 -= $diff;
		}

		$sourceX = $crop1;
		$sourceY = 0;
		return array(
			$sourceX,
			$sourceY
		);
	}

	/**
	 * Check the ratio and create an array
	 *
	 * @param string $ratio
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getRatio($ratio) {
		$ratio = GeneralUtility::trimExplode(':', $ratio, TRUE);
		if (sizeof($ratio) !== 2) {
			throw new \Exception('Ratio have to be in the format of e.g. "1:1" oder "16:9"', 34627384862);
		}
		return $ratio;
	}
}
